<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Role;
use App\Models\state;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserImg;
use App\Models\Userimg as ModelsUserimg;
use Faker\Provider\de_AT\Address as De_ATAddress;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use Symfony\Component\HttpFoundation\Session\Session as SessionSession;

class UserController extends Controller
{
    // signup registration
    public function userData()
    {
        $user = User::get();
        return view('signup', compact('user'));
    }

    public function addUser(Request $req)
    {
        $user = new User();
        $uploadPath = 'media/images';
        if ($req->hasFile('profileImg')) {
            $image = $req->file('profileImg');
            $name = $image->getClientOriginalName();
            $image->move($uploadPath, $name);
            chmod($uploadPath . '/' . $name, 0777);
            $imgPath = $uploadPath . '/' . $name;
        } else {
            $imgPath = null;
        }
        $user->profileImg = $imgPath;
        $user->name = $req->name;
        $user->email = $req->email;
        $user->phone = $req->phone;
        $user->password = Hash::make($req->pass);
        $user->dob = $req->dob;
        $user->gender = $req->gender;
        $user->role = $req->role;
        $user->uId = Str::uuid();
        $user->status=$req->status;
        $user->save();
        $this->sendEmail('NewUser', $req->email, $req->name, $req->name, $req->phone, $req->email);
        return redirect('/home/login')->with('sucess_message', 'Data added successfully.');
    }


    // login
    public function showlogin()
    {
        $user = User::get();
        return view('login', compact('user'));
    }

    public function loginData(Request $req)
    {
        $user = User::where('phone', $req->phone)->first();
        if ($user) {
            if (Hash::check($req->pass, $user->password)) {
                Auth::login($user);
                return redirect('/dashbord');
            } else {
                return redirect('/home/login');
            }
        } else {
            return redirect('/home/login');
        }
    }
    public function showdashbord()
    {
        $pincodeCounts = Address::select('pincode', DB::raw('count(*) as count'))->groupBy('pincode')->pluck('count', 'pincode')->toArray();

        $pincodes = array_keys($pincodeCounts);
        $userCounts = array_values($pincodeCounts);

        $statusUser = User::select('status', DB::raw('count(*) as total'))
                            ->groupBy('status')
                            ->get();
        
        return view('dashbord', compact('pincodes', 'userCounts','statusUser'));

    }


    // forget password
    public function forgetPass()
    {
        return view('forgetPass')->with('error', 'email does not valid.');
    }

    public function forgetData(Request $req)
    {
        $user = User::where('email', $req->email)->first();
        error_log($user);
        $otp = mt_rand(100000, 999999);
        $user->otp = $otp;
        $user->save();
        $this->sendEmail('resetPass', $req->email, $req->name, $req->name, $req->phone, $req->email, $otp);
        return response()->json([
            'status' => 200,
            'message' => 'Information saved sucessfully !'
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        error_log($user);
        if ($user && $user->otp == $request->otp) {
            return response()->json([
                'status' => 200,
                'message' => 'OTP is verified. You can reset your password now.',
            ]);
        } else {
            return response()->json([
                'status' => 422,
                'message' => 'Invalid OTP. Please try again.',
            ]);
        }
    }

    public function changePass(Request $req)
    {
        $user = User::where('email', $req->email)->first();
        $user->password = Hash::make($req->newPass);
        $user->update();
        return response()->json([
            'status' => 200,
            'message' => 'password updated sucessfilly.',
            'user' => $user
        ]);
    }

    // show user data
    public function showUserData()
    {
        $user = User::where('deleteId', 0)->with('rolee')->orderBy('id', 'DESC')->with('userImgs')->get();
        $role = Role::get();
        // $userImg=Userimg::where('deleteId',0)->get();
        return view('userDetails', compact('user', 'role'));
    }

    // delete userData
    public function deleteData(Request $req)
    {
        $user = User::find($req->userId);
        $user->delete();
        return redirect()->back()->with('message', 'Data deleted successfully.');
    }

    // Edit userData
    public function editUser(Request $req)
    {
        $user = User::find($req->userId);
        $uploadPath = 'media/images';
        if ($req->hasFile('profileImg')) {
            $image = $req->file('profileImg');
            $name = $image->getClientOriginalName();
            $image->move($uploadPath, $name);
            chmod($uploadPath . '/' . $name, 0777);
            $imgPath = $uploadPath . '/' . $name;
            if (file_exists($user->profileImg)) {
                unlink($user->profileImg);
            }
        } else {
            $imgPath = User::where('id', $req->userId)->first();
            $imgPath = $imgPath['profileImg'];
        }
        $user->profileImg = $imgPath;
        $user->name = $req->name;
        $user->email = $req->email;
        $user->phone = $req->phone;
        $user->dob = $req->dob;
        $user->gender = $req->gender;
        $user->role = $req->role;
        $user->status=$req->status;
        $user->update();
        return redirect()->back()->with('sucess_message', 'Data update successfully.');
    }

    // roles
    public function showRolesData()
    {
        $role = Role::orderBy('id', 'DESC')->get();
        return view('roles', compact('role'));
    }
    public function addRoles(Request $req)
    {
        $role = new Role();
        $role->name = $req->name;
        $role->slug = Str::slug($req->name, '-');
        $role->status = $req->status;
        $role->save();
        return redirect()->back();
    }


    // address
    public function addAddress(Request $req, $id)
    {
        $address = Address::where('userId', $id)->with('addstate')->orderBy('id', 'DESC')->get();
        $state = State::get();
        $user = User::where('id', $id)->first();
        // $addresses = UserAddress::where('pincode', $pincode)->get();
        return view('address', compact('address', 'user', 'state'));
    }

    public function addAddressData(Request $req)
    {

        if ($req->addressId) {
            $address = Address::find($req->addressId);
        } else {
            $address = new Address();
            $address->userId = $req->id;
        }

        $address->address1 = $req->address1;
        $address->address2 = $req->address2;
        $address->addressType = $req->addressType;
        $address->state = $req->state;
        $address->city = $req->city;
        $address->pincode = $req->pincode;
        $address->landmark = $req->landmark;

        if ($req->addressId) {
            $address->update();
            return redirect()->back()->with('sucess_message', 'Data updateed successfully.');
        } else {
            $address->save();
            return redirect()->back()->with('sucess_message', 'Data added successfully.');
        }
    }

    public function deleteAddress(Request $req)
    {
        $address = Address::find($req->id);
        $address->delete();
        return redirect()->back()->with('error', 'Data deleted successfully.');
    }


    public function logout()
    {
        Auth::logout();
        return redirect('/home/login');
    }

    // userImgs add
    public function addUserImgs(Request $req)
    {
        $uploadPath = 'media/images';
        if ($req->hasFile('images')) {
            foreach ($req->file('images') as $image) {
                $name = time() . '_' . $image->getClientOriginalName();
                $image->move($uploadPath, $name);
                chmod($uploadPath . '/' . $name, 0777);
                $imgPath = $uploadPath . '/' . $name;
                $userImgs = new UserImg();
                $userImgs->userId = $req->userId;
                $userImgs->images = $imgPath;
                $userImgs->save();
            }
        }
        return redirect()->back()->with('sucess_message', 'Data added successfully.');
    }

    public function deleteImgs(Request $req)
    {
        $userImgs = UserImg::find($req->hiddenId);
        $userImgs->delete();
        return redirect()->back()->with('error_message', 'Data deleted successfully.');
    }

    // pincide search
    public function searchPinCode(Request $req)
    {
        $data = $req->pincode;
        $role = Role::get();
        $user = User::with("addresses")->with('rolee')->whereHas('addresses', function ($query) use ($data) {
            $query->where('pincode', $data);
        })->get();
        return view('pincode', compact("user", "role"));
    }

    // add excelsheets files
    public function showExcel(Request $request)
    {
        try {
            $file = $request->file('excelFile');
            $filename = $file->getClientOriginalName();
            $uploadpath = 'storage/ExcelFiles/User/';
            $filepath = 'storage/ExcelFiles/User/' . $filename;
            $file->move($uploadpath, $filename);

            chmod('storage/ExcelFiles/User/' . $filename, 0777);
            $xls_file = $filepath;
            $reader = new Xlsx();
            $spreadsheet = $reader->load($xls_file);
            $loadedSheetName = $spreadsheet->getSheetNames();

            $writer = new Csv($spreadsheet);
            $sheetName = $loadedSheetName[0];
            foreach ($loadedSheetName as $sheetIndex => $loadedSheetName) {
                $writer->setSheetIndex($sheetIndex);
                $writer->save($loadedSheetName . '.csv');
            }
            $inf = $sheetName . '.csv';
            $fileD = fopen($inf, "r");
            $column = fgetcsv($fileD);
            while (!feof($fileD)) {
                $rowData[] = fgetcsv($fileD);
            }
            $skip_lov = array();
            $counter = 0;
            $failed = 0;
            foreach ($rowData as $value) {
                // return $value;
                if (empty($value)) {
                    $counter--;
                } else {
                    $fieldData = new User();  //name of modal
                    $fieldData->name = $value[0];  //name of database feild = colm no in xls
                    $fieldData->email = $value[1];
                    $fieldData->phone = $value[2];
                    $fieldData->password = Hash::make($value[3]);
                    $fieldData->dob = $value[4];
                    $fieldData->gender = $value[5];
                    $fieldData->role = Str::slug($value[6] . '-');
                    $fieldData->uId = Str::uuid();
                    $fieldData->save();
                }
                $counter++;
            }
            return redirect()->back()->with('sucess_message', 'Data updateed successfully.');
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function showAddExcel(Request $request)
    {
        try {
            $file = $request->file('addressFile');
            $filename = $file->getClientOriginalName();
            $uploadpath = 'storage/ExcelFiles/User/';
            $filepath = 'storage/ExcelFiles/User/' . $filename;
            $file->move($uploadpath, $filename);

            chmod('storage/ExcelFiles/User/' . $filename, 0777);
            $xls_file = $filepath;
            $reader = new Xlsx();
            $spreadsheet = $reader->load($xls_file);
            $loadedSheetName = $spreadsheet->getSheetNames();

            $writer = new Csv($spreadsheet);
            $sheetName = $loadedSheetName[0];
            foreach ($loadedSheetName as $sheetIndex => $loadedSheetName) {
                $writer->setSheetIndex($sheetIndex);
                $writer->save($loadedSheetName . '.csv');
            }
            $inf = $sheetName . '.csv';
            $fileD = fopen($inf, "r");
            $column = fgetcsv($fileD);
            while (!feof($fileD)) {
                $rowData[] = fgetcsv($fileD);
            }
            $skip_lov = array();
            $counter = 0;
            $failed = 0;
            foreach ($rowData as $value) {
                if (empty($value)) {
                    $counter--;
                } else {
                    $user = User::where('name',$value[0])->first();
                    $state = State::where('state',$value[4])->first();

                    $fieldData = new Address(); //name of modal
                    $fieldData->userId = $user->id; //name of database feild = colm no in xls
                    $fieldData->address1 = $value[1]; 
                    $fieldData->address2 = $value[2];
                    $fieldData->addressType = $value[3];
                    $fieldData->state = $state->id; //name of database feild = colm no in xls
                    $fieldData->city = $value[5];
                    $fieldData->pincode = $value[6];
                    $fieldData->landMark = $value[7];
                    $fieldData->save();
                }
                $counter++;
            }
            return redirect()->back()->with('sucess_message', 'Data updateed successfully.');
        } catch (\Exception $e) {
            return $e;
        }
    }

}
