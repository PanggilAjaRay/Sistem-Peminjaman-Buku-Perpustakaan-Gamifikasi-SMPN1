<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Member;
use OpenSpout\Writer\XLSX\Writer as XLSXWriter;
use OpenSpout\Reader\XLSX\Reader as XLSXReader;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;

class MemberForm extends Component
{
    use WithPagination, WithFileUploads;

    public $name, $nis, $class, $birth_date, $email, $phone;
    public $memberId;
    public $isOpen = false;
    public $search = '';
    public $generatedPassword = '';
    public $showPassword = false;
    
    // Import properties
    public $importFile;
    public $showImportModal = false;
    public $importResults = [];
    public $showImportResults = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'nis' => 'required|string|max:255|unique:members,nis',
        'class' => 'required|string|max:255',
        'birth_date' => 'required|date',
        'email' => 'nullable|email|max:255',
        'phone' => 'nullable|string|max:20',
    ];

    public function render()
    {
        $members = Member::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('nis', 'like', '%' . $this->search . '%')
            ->orWhere('class', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.member-form', [
            'members' => $members
        ]);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->nis = '';
        $this->class = '';
        $this->birth_date = '';
        $this->email = '';
        $this->phone = '';
        $this->memberId = null;
        $this->generatedPassword = '';
        $this->showPassword = false;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'nis' => 'required|string|max:255|unique:members,nis,' . $this->memberId,
            'class' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $member = Member::updateOrCreate(['id' => $this->memberId], [
            'name' => $this->name,
            'nis' => $this->nis,
            'class' => $this->class,
            'birth_date' => $this->birth_date,
            'email' => $this->email,
            'phone' => $this->phone,
        ]);

        // Create or update user account for student
        if (!$this->memberId) {
            // Only create user for new members
            $password = $member->generatePassword();
            
            \App\Models\User::create([
                'name' => $member->name,
                'nis' => $member->nis,
                'member_id' => $member->id,
                'role' => 'student',
                'password' => $password,
            ]);

            $this->generatedPassword = $password;
            $this->showPassword = true;
        }

        session()->flash('message', 
            $this->memberId ? 'Anggota berhasil diperbarui.' : 'Anggota berhasil ditambahkan.');

        if (!$this->showPassword) {
            $this->closeModal();
            $this->resetInputFields();
        }
    }

    public function edit($id)
    {
        $member = Member::findOrFail($id);
        $this->memberId = $id;
        $this->name = $member->name;
        $this->nis = $member->nis;
        $this->class = $member->class;
        $this->birth_date = $member->birth_date->format('Y-m-d');
        $this->email = $member->email;
        $this->phone = $member->phone;

        $this->openModal();
    }

    public function closePasswordModal()
    {
        $this->showPassword = false;
        $this->generatedPassword = '';
        $this->closeModal();
        $this->resetInputFields();
    }

    public function exportToExcel()
    {
        $fileName = 'members_' . date('Y-m-d') . '.xlsx';
        $filePath = storage_path('app/' . $fileName);

        $writer = new XLSXWriter();
        $writer->openToFile($filePath);

        // Create header style (bold)
        $headerStyle = new Style();
        $headerStyle->setFontBold();

        // Add header row
        $headerRow = Row::fromValues(['Nama', 'NIS', 'Kelas', 'Tanggal Lahir', 'Email', 'Telepon'], $headerStyle);
        $writer->addRow($headerRow);

        // Add data rows
        $members = Member::all();
        foreach ($members as $member) {
            $row = Row::fromValues([
                $member->name,
                $member->nis,
                $member->class,
                $member->birth_date->format('Y-m-d'),
                $member->email ?? '',
                $member->phone ?? ''
            ]);
            $writer->addRow($row);
        }

        $writer->close();

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    public function downloadTemplate()
    {
        $fileName = 'template_import_members.xlsx';
        $filePath = storage_path('app/' . $fileName);

        $writer = new XLSXWriter();
        $writer->openToFile($filePath);

        // Create header style (bold)
        $headerStyle = new Style();
        $headerStyle->setFontBold();

        // Add header row
        $headerRow = Row::fromValues(['Nama', 'NIS', 'Kelas', 'Tanggal Lahir', 'Email', 'Telepon'], $headerStyle);
        $writer->addRow($headerRow);

        // Add example row
        $exampleRow = Row::fromValues([
            'Ahmad Rizki',
            '12345',
            '7A',
            '2010-05-15',
            'ahmad@example.com',
            '08123456789'
        ]);
        $writer->addRow($exampleRow);

        $writer->close();

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    public function openImportModal()
    {
        $this->showImportModal = true;
        $this->importFile = null;
        $this->importResults = [];
        $this->showImportResults = false;
    }

    public function closeImportModal()
    {
        $this->showImportModal = false;
        $this->importFile = null;
        $this->importResults = [];
        $this->showImportResults = false;
    }

    public function importFromExcel()
    {
        $this->validate([
            'importFile' => 'required|file|mimes:xlsx|max:10240',
        ]);

        $this->importResults = [
            'success' => 0,
            'errors' => [],
            'skipped' => 0
        ];

        try {
            $reader = new XLSXReader();
            $reader->open($this->importFile->getRealPath());

            $rowNumber = 0;
            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $row) {
                    $rowNumber++;
                    
                    // Skip header row
                    if ($rowNumber === 1) {
                        continue;
                    }

                    $cells = $row->toArray();
                    
                    // Skip empty rows
                    if (empty(array_filter($cells))) {
                        continue;
                    }

                    // Validate required fields
                    if (empty($cells[0]) || empty($cells[1]) || empty($cells[2]) || empty($cells[3])) {
                        $this->importResults['errors'][] = "Baris $rowNumber: Field wajib (Nama, NIS, Kelas, Tanggal Lahir) tidak boleh kosong";
                        $this->importResults['skipped']++;
                        continue;
                    }

                    $name = $cells[0];
                    $nis = $cells[1];
                    $class = $cells[2];
                    $birthDate = $cells[3];
                    $email = $cells[4] ?? null;
                    $phone = $cells[5] ?? null;

                    // Validate email if provided
                    if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $this->importResults['errors'][] = "Baris $rowNumber: Format email tidak valid";
                        $this->importResults['skipped']++;
                        continue;
                    }

                    // Parse and validate date
                    try {
                        $birthDateParsed = \Carbon\Carbon::parse($birthDate)->format('Y-m-d');
                    } catch (\Exception $e) {
                        $this->importResults['errors'][] = "Baris $rowNumber: Format tanggal lahir tidak valid";
                        $this->importResults['skipped']++;
                        continue;
                    }

                    // Clean empty values
                    if ($email === '-' || empty($email)) $email = null;
                    if ($phone === '-' || empty($phone)) $phone = null;

                    // Create or update member
                    try {
                        // Check if member exists
                        $existingMember = Member::where('nis', $nis)->first();
                        
                        $member = Member::updateOrCreate(
                            ['nis' => $nis],
                            [
                                'name' => $name,
                                'class' => $class,
                                'birth_date' => $birthDateParsed,
                                'email' => $email,
                                'phone' => $phone,
                            ]
                        );

                        // Create user account only for new members
                        if (!$existingMember) {
                            $password = $member->generatePassword();
                            \App\Models\User::create([
                                'name' => $member->name,
                                'nis' => $member->nis,
                                'member_id' => $member->id,
                                'role' => 'student',
                                'password' => $password,
                            ]);
                        } else {
                            // Update user name if member was updated
                            \App\Models\User::where('nis', $nis)->update([
                                'name' => $member->name
                            ]);
                        }

                        $this->importResults['success']++;
                    } catch (\Exception $e) {
                        $this->importResults['errors'][] = "Baris $rowNumber: Gagal menyimpan data - " . $e->getMessage();
                        $this->importResults['skipped']++;
                    }
                }
            }

            $reader->close();

            $this->showImportResults = true;
            
            if ($this->importResults['success'] > 0) {
                session()->flash('message', $this->importResults['success'] . ' anggota berhasil diimpor.');
            }

        } catch (\Exception $e) {
            $this->importResults['errors'][] = 'Error membaca file: ' . $e->getMessage();
            $this->showImportResults = true;
        }
    }

    public function delete($id)
    {
        Member::find($id)->delete();
        session()->flash('message', 'Anggota berhasil dihapus.');
    }
}
