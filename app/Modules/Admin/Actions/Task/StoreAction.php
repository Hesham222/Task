<?php
namespace Admin\Actions\Task;
use App\Http\Traits\FileTrait;
use Illuminate\Http\Request;
use Admin\Models\{
    Task
};
class StoreAction
{
    public function execute(Request $request)
    {
        // encryption code
        $file = public_path($request->input('file'));
        $key = 'elVrE8gs73kjoR1G6yj38Q3iSsZ7lnBDEd0aBU2Al0ii/4dNpGFSxxBO+3T4OT1I';
        $code = file_get_contents($file); //Get the code to be encrypted.
        $encrypted_code = FileTrait::my_encrypt($code, $key); //Encrypt the code.
        file_put_contents($file, $encrypted_code); //Save the encrypted code somewhere.

        $record =  Task::create([
            'name'       => $request->input('file'),
        ]);
        return $record;
    }

}
