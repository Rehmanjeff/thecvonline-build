<?php

namespace App\Http\Controllers;

use App\Mail\EmailTemplateMail;
use stdClass;
use App\Models\Email;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Input;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Repositories\EmailRepositoryInterface;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    private $repository;

    public function __construct(EmailRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        try {
            $emails = $this->repository->getPaginated($request->all());
    
            return response()->success("Emails fetched successfully", ['paginated_items' => $emails]);
        } catch(\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function upload(Request $request)
    {
        try {
            DB::beginTransaction();
            Validator::make($request->all(), [
                'file' => 'required',
            ])->validate();

            $file = $request->file('file');
            
            $spreadsheet = IOFactory::load($file);
        
            $sheet = $spreadsheet->getActiveSheet();
            foreach ($sheet->getRowIterator() as $row) {
                $rowData = [];
                foreach ($row->getCellIterator() as $cell) {
                    $rowData[] = $cell->getValue();
                }
                if (filter_var($rowData[0], FILTER_VALIDATE_EMAIL)) {
                    $data['email'] = $rowData[0];
                    $data['requirements'] = $rowData[1];
                    $data['city'] = $rowData[2];
                    
                    $this->repository->updateOrCreate($data);
                }
            }
            DB::commit();

            return response()->success('Emails uploaded successfully');
        } catch(\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = $this->repository->storeValidation($request->all())->validate();
            $email = $this->repository->store($data);
            DB::commit();

            return response()->success('Email created successfully', ['item' => $email]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }

    public function generateExcel(Request $request)
    {
        $requirements = [
            'civil engineer',
            'mechanical engineer',
            'electrical engineer',
            'software engineer',
            'hardware engineer',
            'chemical engineer',
            'industrial engineer',
            'environmental engineer',
            'biomedical engineer',
            'aerospace engineer',
        ];

        $records = [];
        for ($i = 0; $i < 500; $i++) {
            $email = "user{$i}@example.com";
            $requirement = $requirements[array_rand($requirements)];
            $city = "City{$i}";
            $records[] = [
                "email" => $email,
                "requirements" => $requirement,
                "city" => $city,
            ];
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray($records, null, 'A1');

        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);

        // Set header row
        $sheet->setCellValue('A1', 'Email');
        $sheet->setCellValue('B1', 'Requirements');
        $sheet->setCellValue('C1', 'City');
        $sheet->getStyle('A1:C1')->getFont()->setBold(true);

        // Save the Excel file
        $writer = new Xlsx($spreadsheet);
        $writer->save('dummy_records.xlsx');

        // Provide a download link
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="dummy_records.xlsx"');
        header('Content-Length: ' . filesize('dummy_records.xlsx'));
        readfile('dummy_records.xlsx');
    }

    public function update(Request $request, $id)
    {
        try{
            DB::beginTransaction();
            $data = $this->repository->updateValidation($request->all())->validate();
            $email = $this->repository->update($data, $id);
            DB::commit();

            return response()->success('Email updated successfully', ['item' => $email]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }

    public function destroy($id)
    {
        try {
            $this->repository->delete($id);
            
            return response()->success('Email deleted successfully');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function getCount(Request $request)
    {
        try {
            $data = $request->all();
            $count = new stdClass();

            $count->emails = $this->repository->model->filters($data)->count();
            $count->cities = $this->repository->model->filters($data)->distinct('city')->count();
            $count->requirements = $this->repository->model->filters($data)->distinct('requirements')->count();

            return response()->success('Counts fetched successfully', ['item' => $count]);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function getTemplate(Request $request)
    {
        try {
            $emailTemplate = EmailTemplate::first();

            return response()->success('Template fetched successfully', ['item' => $emailTemplate]);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function setTemplate(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = Validator::make($request->all(), [
                'subject' => 'required',
                'content' => 'required',
                'attachment' => 'required',
            ])->validate();

            $uploadedFile = $request->file('attachment');

            $filename = time() . rand(1000, 9999). '.' . $uploadedFile->getClientOriginalExtension();
            $data['attachment'] = $uploadedFile->storeAs('attachment', $filename, 'public');

            $emailTemplate = EmailTemplate::updateOrCreate([], $data);
            DB::commit();

            return response()->success('Template updated successfully', ['item' => $emailTemplate]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }

    public function sendEmail(Request $request)
    {
        try {
            $emailTemplate = EmailTemplate::first();

            throw_if(!$emailTemplate, "Please set Email template first");

            Mail::to($this->repository->model->first())->send(new EmailTemplateMail($emailTemplate));

            return response()->success('Email sent successfully');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }   
    }

    public function getStaticData(Request $request)
    {
        try {
            $data = new stdClass();

            $data->cities = $this->repository->model->distinct('city')->pluck('city')->toArray();
            $data->requirements = $this->repository->model->distinct('requirements')->pluck('requirements')->toArray();

            return response()->success('Counts fetched successfully', ['item' => $data]);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }   
    }
}
