<?php

namespace App\Livewire\Hakakses;

use App\Models\Employee;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class Form extends Component
{
    public $data, $previous, $roleData = [], $employeeData = [];
    public $email, $employee_id, $password, $role, $hakAkses = [];

    public function submit()
    {
        $this->validate([
            'hakAkses' => 'required',
            'role' => 'required',
            'email' => 'required|email|unique:users,email,' . $this->data->id,
            'employee_id' => 'required',
        ]);

        DB::transaction(function () {
            if (!$this->data->exists) {
                if (User::where('email', $this->email)->withTrashed()->count() > 0) {
                    session()->flash('danger', 'Email ' . $this->email . ' sudah ada');
                    return $this->render();
                }
                $this->data->email = $this->email;
                $this->data->password = Hash::make($this->email);
            }
            $this->data->employee_id = $this->employee_id;
            $this->data->save();

            $this->data->syncPermissions($this->hakAkses);

            $this->data->syncRoles($this->role);
            session()->flash('success', 'Berhasil menyimpan data');
        });
        $this->redirect($this->previous);
    }

    public function mount(User $data)
    {
        $this->previous = url()->previous();
        $this->roleData = Role::all()->toArray();
        $this->employeeData = Employee::orderBy('nama')->get()->toArray();
        $this->data = $data;
        $this->fill($this->data->toArray());
        $this->role = $this->data->getRoleNames()?->first();
        $this->hakAkses = $this->data->getPermissionNames()->toArray();
    }

    public function changeRole()
    {
        if ($this->role == 'administrator') {
            foreach (Permission::all() as $id => $subRow) {
                $this->hakAkses[] = $subRow->nama;
            }
        } else {
            // $this->hakAkses = [];
        }
    }

    public function resetKataSandi()
    {
        $this->data->password = Hash::make($this->email);
        $this->data->save();

        session()->flash('success', 'Berhasil menyimpan data');
    }

    public function render()
    {
        return view('livewire.hakakses.form');
    }
}
