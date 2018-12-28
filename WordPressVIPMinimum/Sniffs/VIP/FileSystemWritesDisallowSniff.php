<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace WordPressVIPMinimum\Sniffs\VIP;

use WordPress\AbstractFunctionRestrictionsSniff;

/**
 * Disallow Filesystem writes.
 *
 * @link https://vip.wordpress.com/documentation/vip-go/code-review-blockers-warnings-notices/#filesystem-operations
 *
 * @package VIPCS\WordPressVIPMinimum
 *
 * @since   0.5.0
 */
class FileSystemWritesDisallowSniff extends AbstractFunctionRestrictionsSniff {

	/**
	 * If true, an error will be thrown; otherwise a warning.
	 *
	 * @var bool
	 */
	public $error = true;

	/**
	 * Groups of functions to restrict.
	 *
	 * Example: groups => array(
	 *  'lambda' => array(
	 *      'type'      => 'error' | 'warning',
	 *      'message'   => 'Use anonymous functions instead please!',
	 *      'functions' => array( 'file_get_contents', 'create_function' ),
	 *  )
	 * )
	 *
	 * @return array
	 */
	public function getGroups() {
		$groups = [
			'file_ops' => [
				'type'      => 'error',
				'message'   => 'Filesystem writes are forbidden, you should not be using %s()',
				'functions' => [
					'delete',
					'file_put_contents',
					'flock',
					'fputcsv',
					'fputs',
					'fwrite',
					'ftruncate',
					'is_writable',
					'is_writeable',
					'link',
					'rename',
					'symlink',
					'tempnam',
					'touch',
					'unlink',
				],
			],
			'directory' => [
				'type'      => 'error',
				'message'   => 'Filesystem writes are forbidden, you should not be using %s()',
				'functions' => [
					'mkdir',
					'rmdir',
				],
			],
			'chmod' => [
				'type'      => 'error',
				'message'   => 'Filesystem writes are forbidden, you should not be using %s()',
				'functions' => [
					'chgrp',
					'chown',
					'chmod',
					'lchgrp',
					'lchown',
				],
			],
		];

		/*
		 * Maintain old behaviour - allow for changing the error type from the ruleset
		 * using the `error` property.
		 */
		if ( false === $this->error ) {
			foreach ( $groups as $group_name => $details ) {
				$groups[ $group_name ]['type'] = 'warning';
			}
		}

		return $groups;
	}

}
