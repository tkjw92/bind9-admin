<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class zones extends Controller
{
    public function viewZone()
    {
        $zones = DB::table('tb_zones')->get();
        $ptrs = DB::table('tb_ptr')->get();

        return view('index', compact('zones', 'ptrs'));
    }

    public function addZone(Request $request)
    {
        $zones = DB::table('tb_zones');

        if ($zones->where('name', $request->name)->count() > 0) {
            return back();
        } else {
            $zones->insert([
                'type' => $request->type,
                'name' => $request->name,
                'peers' => $request->peers
            ]);

            shell_exec('sudo ' . public_path() . '/backend/bin/python ./backend/main.py');

            return back();
        }
    }

    public function deleteZone($name)
    {
        DB::table('tb_zones')->where('name', $name)->delete();
        DB::table('tb_records')->where('zone', $name)->delete();

        shell_exec('sudo ' . public_path() . '/backend/bin/python ./backend/main.py');

        return redirect('/');
    }

    public function editZone($name)
    {
        $records = DB::table('tb_records')->where('zone', $name)->get();
        $zone = DB::table('tb_zones')->where('name', $name)->first();
        $ptr = DB::table('tb_ptr')->where('name', $name)->count();

        return view('editZone', compact('name', 'records', 'zone', 'ptr'));
    }

    public function addRecord($name, Request $request)
    {
        DB::table('tb_records')->insert([
            'zone' => $name,
            'type' => $request->type,
            'name' => $request->name,
            'content' => $request->content
        ]);

        shell_exec('sudo ' . public_path() . '/backend/bin/python ./backend/main.py');

        return back();
    }

    public function deleteRecord($id)
    {
        DB::table('tb_records')->where('id', $id)->delete();

        shell_exec('sudo ' . public_path() . '/backend/bin/python ./backend/main.py');

        return back();
    }

    public function editRecord($id, Request $request)
    {
        $content = $request->content;

        if ($request->type == 'cname' || $request->type == 'mx') {
            $content = $request->content . '.';
        }

        DB::table('tb_records')->where('id', $id)->update([
            'type' => $request->type,
            'name' => $request->name,
            'content' => $content
        ]);

        shell_exec('sudo ' . public_path() . '/backend/bin/python ./backend/main.py');

        return back();
    }

    public function updatePeers(Request $request)
    {
        DB::table('tb_zones')->where('name', $request->zone)->update([
            'peers' => $request->peers
        ]);

        DB::table('tb_ptr')->where('name', $request->zone)->update([
            'peers' => $request->peers
        ]);

        shell_exec('sudo ' . public_path() . '/backend/bin/python ./backend/main.py');

        return back();
    }

    public function viewOptions()
    {
        $conf = DB::table('tb_config')->first();

        return view('options', compact('conf'));
    }

    public function options(Request $request)
    {
        $conf = DB::table('tb_config');

        if (isset($request->allowed)) {
            $conf->update(['allowed' => $request->allowed]);
        } else {
            $conf->update(['allowed' => 'any;']);
        }

        if (isset($request->recursion)) {
            $conf->update(['recursion' => 'yes']);
        } else {
            $conf->update(['recursion' => 'no']);
        }

        $conf->update([
            'f1' => $request->f1,
            'f2' => $request->f2,
            'f3' => $request->f3,
            'f4' => $request->f4,
        ]);

        shell_exec('sudo ' . public_path() . '/backend/bin/python ./backend/main.py');

        return back();
    }

    public function addPtr(Request $request)
    {
        $ptr = DB::table('tb_ptr');
        $name = DB::table('tb_zones')->where('name', $request->name)->first();

        if ($ptr->where('ptr', $request->ptr)->count() > 0) {
            return back();
        } else {
            $ptr->insert([
                'name' => $request->name,
                'ptr' => $request->ptr,
                'type' => $name->type,
                'peers' => $name->peers
            ]);

            return back();
        }
    }

    public function viewZonePTR($ptr)
    {
        $ptr = DB::table('tb_ptr')->where('ptr', $ptr)->first();
        $records = DB::table('tb_records_ptr')->where('ptr', $ptr->ptr)->get();

        return view('editZonePTR', compact('ptr', 'records'));
    }

    public function addRecordPTR(Request $request)
    {
        $ptr = DB::table('tb_records_ptr');

        $ptr->insert([
            'ptr' => $request->ptr,
            'name' => $request->name,
            'content' => $request->content
        ]);

        shell_exec('sudo ' . public_path() . '/backend/bin/python ./backend/main.py');

        return back();
    }

    public function deleteZonePTR($ptr)
    {
        DB::table('tb_ptr')->where('ptr', $ptr)->delete();
        DB::table('tb_records_ptr')->where('ptr', $ptr)->delete();

        shell_exec('sudo ' . public_path() . '/backend/bin/python ./backend/main.py');

        return redirect('/');
    }

    public function editRecordPTR(Request $request)
    {
        DB::table('tb_records_ptr')->where('id', $request->id)->update([
            'name' => $request->name,
            'content' => $request->content
        ]);

        shell_exec('sudo ' . public_path() . '/backend/bin/python ./backend/main.py');

        return back();
    }

    public function deleteRecordPTR($id)
    {
        DB::table('tb_records_ptr')->where('id', $id)->delete();

        shell_exec('sudo ' . public_path() . '/backend/bin/python ./backend/main.py');

        return back();
    }

    public function viewSetting()
    {
        return view('setting');
    }

    public function setting(Request $request)
    {
        DB::table('tb_account')->update([
            'username' => $request->username,
            'password' => $request->password
        ]);

        return redirect('/');
    }
}
