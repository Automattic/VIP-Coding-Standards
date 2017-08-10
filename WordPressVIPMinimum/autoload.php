<?php

namespace WordPressVIPMinimum;

function load_phpcs_helper_file() {
	foreach( \PHP_CodeSniffer\Util\Standards::getInstalledStandardDetails( 'WordPress' ) as $standard => $details ) {
		if ( 'WordPress' === $standard ) {
			require_once( $details['path'] . '/PHPCSAliases.php' );
		}
	}
	if ( ! class_exists( '\PHP_CodeSniffer_Standards_AbstractScopeSniff' ) ) {
		class_alias( 'PHP_CodeSniffer\Sniffs\AbstractScopeSniff', '\PHP_CodeSniffer_Standards_AbstractScopeSniff' );
	}
	if ( ! class_exists( '\WordPress_AbstractArrayAssignmentRestrictionsSniff' ) ) {
		class_alias( '\WordPress\AbstractArrayAssignmentRestrictionsSniff', '\WordPress_AbstractArrayAssignmentRestrictionsSniff' );
	}
	if ( ! class_exists( '\WordPress_Sniffs_VIP_RestrictedFunctionsSniff' ) ) {
		class_alias( '\WordPress\Sniffs\VIP\RestrictedFunctionsSniff', '\WordPress_Sniffs_VIP_RestrictedFunctionsSniff' );
	}
}

load_phpcs_helper_file();