<?php

/*
 * test.php
 *
 * @(#) $Id: test.php,v 1.2 2006/07/17 04:11:13 mlemos Exp $
 *
 */

$__tests=array(
		'singleclienterror'=>array(
			'script'=>'../test_form.php',
			'generatedfile'=>'generated/test_form.php.html',
			'expectedfile'=>'expect/test_form.php.html',
			'options'=>array(
				'ShowAllErrors'=>0,
				'ErrorMessagePrefix'=>''
				)
				),
		'allclienterrors'=>array(
			'script'=>'../test_form.php',
			'generatedfile'=>'generated/all_client_errors_test_form.php.html',
			'expectedfile'=>'expect/all_client_errors_test_form.php.html',
			'options'=>array(
				'ShowAllErrors'=>1,
				'ErrorMessagePrefix'=>''
				)
				),
		'singleservererror'=>array(
			'script'=>'../test_form.php',
			'generatedfile'=>'generated/server_test_form.php.html',
			'expectedfile'=>'expect/server_test_form.php.html',
			'options'=>array(
				'ShowAllErrors'=>0,
				'ErrorMessagePrefix'=>''
				),
			'post'=>array(
				'doit'=>'1'
				)
				),
		'allservererrors'=>array(
			'script'=>'../test_form.php',
			'generatedfile'=>'generated/all_server_errors_test_form.php.html',
			'expectedfile'=>'expect/all_server_errors_test_form.php.html',
			'options'=>array(
				'ShowAllErrors'=>1,
				'ErrorMessagePrefix'=>''
				),
			'post'=>array(
				'doit'=>'1'
				)
				),
				);

				define('__TEST',1);
				for($__different=$__test=$__checked=0, Reset($__tests); $__test<count($__tests); Next($__tests), $__test++)
				{
					$__name=Key($__tests);
					$__script=$__tests[$__name]['script'];
					if(!file_exists($__script))
					{
						echo "\n".'Test script '.$__script.' does not exist.'."\n".str_repeat('_',80)."\n";
						continue;
					}
					echo 'Test "'.$__name.'": ... ';
					flush();
					if(IsSet($__tests[$__name]['options']))
					$__test_options=$__tests[$__name]['options'];
					else
					$__test_options=array();
					if(IsSet($__tests[$__name]['post']))
					{
						$_POST=$__tests[$__name]['post'];
						$_SERVER['REQUEST_METHOD']='POST';
					}
					else
					{
						$_POST=array();
						$_SERVER['REQUEST_METHOD']='GET';
					}
					ob_start();
					require($__script);
					$output=ob_get_contents();
					ob_end_clean();
					$generated=$__tests[$__name]['generatedfile'];
					if(!($file = fopen($generated, 'wb')))
					die('Could not create the generated output file '.$generated."\n");
					if(!fputs($file, $output)
					|| !fclose($file))
					die('Could not save the generated output to the file '.$generated."\n");
					$expected=$__tests[$__name]['expectedfile'];
					if(!file_exists($expected))
					{
						echo "\n".'Expected output file '.$expected.' does not exist.'."\n".str_repeat('_',80)."\n";
						continue;
					}
					$diff=array();
					exec('diff '.$expected.' '.$generated, $diff);
					if(count($diff))
					{
						echo "FAILED\n".'Output of script '.$__script.' is different from the expected file '.$expected." .\n".str_repeat('_',80)."\n";
						for($line=0; $line<count($diff); $line++)
						echo $diff[$line]."\n";
						echo str_repeat('_',80)."\n";
						flush();
						$__different++;
					}
					else
					echo "OK\n";
					$__checked++;
				}
				echo $__checked.' test '.($__checked==1 ? 'was' : 'were').' performed, '.($__checked!=$__test ? (($__test-$__checked==1) ? ' 1 test was skipped, ' : ($__test-$__checked).' tests were skipped, ') : '').($__different ? $__different.' failed' : 'none has failed').'.'."\n";

				?>