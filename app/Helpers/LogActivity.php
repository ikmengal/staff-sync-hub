<?php


namespace App\Helpers;

use Illuminate\Support\Facades\Request;
use App\Models\LogActivity as LogActivityModel;

class LogActivity
{
	public static function addToLog($subject)
	{
		$log = [];
		$log['subject'] = $subject;
		$log['url'] = Request::fullUrl();
		$log['method'] = Request::method();
		$log['ip'] = Request::ip();
		$log['agent'] = Request::header('user-agent');
		$log['user_id'] = auth()->check() ? auth()->user()->id : 1;
		LogActivityModel::create($log);
	}


	public static function logActivityLists($per_record)
	{
		return LogActivityModel::latest()->paginate($per_record);
	}
}
