"use strict";

var widgetoptsSettingsModule = {
	init: function() {
		var self 	= this;
        jQuery( '.widgetopts-module-settings-container' ).hide();

		self.bindEvents();
    },

	bindEvents: function() {
		var self 	= this;
		var $wpcontent = jQuery( '#wpcontent' );

		$wpcontent.on( 'click', '.widgetopts-toggle-settings, .widgetopts-module-settings-cancel', self.openModal );
		$wpcontent.on( 'click', '.widgetopts-close-modal, .widgetopts-modal-background', self.closeModal );
		$wpcontent.on( 'keyup', self.closeModal );
		$wpcontent.on( 'click', '.widgetopts-toggle-activation', self.moduleToggle );
		$wpcontent.on( 'click', '.widgetopts-module-settings-save', self.saveSettings );
		$wpcontent.on( 'click', '.widgetopts-license-button', self.licenseHandler );

	},

	openModal: function( e ) {
		e.preventDefault();

		var $container = jQuery(this).parents( '.widgetopts-module-card' ).find( '.widgetopts-module-settings-container' ),
			$modalBg = jQuery( '.widgetopts-modal-background' );

		$modalBg.show();
		$container
			.show();

		jQuery( 'body' ).addClass( 'widgetopts-modal-open' );
	},

	closeModal: function( e ) {
		if ( 'undefined' !== typeof e ) {
			e.preventDefault();

			// For keyup events, only process esc
			if ( 'keyup' === e.type && 27 !== e.which ) {
				return;
			}
		}

		jQuery( '.widgetopts-modal-background' ).hide();
		jQuery( '.widgetopts-module-settings-container' ).hide();
		jQuery( 'body' ).removeClass( 'widgetopts-modal-open' );
	},

	moduleToggle: function( e ) {
		e.preventDefault();
		e.stopPropagation();

		var $button = jQuery(this),
			$card = $button.parents( '.widgetopts-module-card' ),
			$buttons = $card.find( '.widgetopts-toggle-activation' ),
			module = $card.attr( 'data-module-id' );

		$buttons.prop( 'disabled', true );

		if ( $button.html() == widgetopts.translation.activate ) {
			var method = 'activate';
		} else {
			var method = 'deactivate';
		}

		widgetoptsSettingsModule.ajaxRequest( module, method, {}, widgetoptsSettingsModule.moduleCallback );
	},

	moduleCallback: function( results ) {
		var module = results.module;
		var method = results.method;

		var $card = jQuery( '#widgetopts-module-card-' + module ),
			$buttons = $card.find( '.widgetopts-toggle-activation' );

		if ( results.errors.length > 0 ) {
			$buttons
				.html( widgetopts.translations.error )
				.addClass( 'button-secondary' )
				.removeClass( 'button-primary' );

			setTimeout( function() {
				widgetoptsSettingsModule.isModuleActive( module );
			}, 1000 );

			return;
		}

		if ( 'activate' === method ) {
			$buttons
				.html( widgetopts.translation.deactivate )
				.addClass( 'button-secondary' )
				.removeClass( 'button-primary' )
				.prop( 'disabled', false );

			$card
				.addClass( 'widgetopts-module-type-enabled' )
				.removeClass( 'widgetopts-module-type-disabled' );

			var newToggleSettingsLabel = widgetopts.translation.show_settings;
		} else {
			$buttons
				.html( widgetopts.translation.activate )
				.addClass( 'button-primary' )
				.removeClass( 'button-secondary' )
				.prop( 'disabled', false );

			$card
				.addClass( 'widgetopts-module-type-disabled' )
				.removeClass( 'widgetopts-module-type-enabled' );

			var newToggleSettingsLabel = widgetopts.translation.show_description;
		}

		if( !$card.hasClass('widgetopts-module-card-no-settings') ){
			$card.find( '.widgetopts-toggle-settings' ).html( newToggleSettingsLabel );
		}
	},

	saveSettings: function( e ) {
		e.preventDefault();

		var $button = jQuery(this);

		if ( $button.hasClass( 'widgetopts-module-settings-save' ) ) {
			var module = $button.parents( '.widgetopts-module-card' ).attr( 'data-module-id' );
		} else {
			var module = '';
		}

		$button.prop( 'disabled', true );

		var data = {
			'--widgetopts-form-serialized-data': jQuery( '#widgetopts-module-settings-form' ).serialize()
		};

		widgetoptsSettingsModule.ajaxRequest( module, 'save', data, widgetoptsSettingsModule.savingCallback );
	},

	savingCallback: function( results ) {
		if ( '' === results.module ) {
			jQuery( '#widgetopts-save' ).prop( 'disabled', false );
		} else {
			jQuery( '#widgetopts-module-card-' + results.module + ' button.widgetopts-module-settings-save' ).prop( 'disabled', false );
		}

		var $container = jQuery( '.widgetopts-module-cards-container' );
		var view = 'grid';

		// console.log( results );
		widgetoptsSettingsModule.clearMessages();
		if ( results.errors.length > 0 || ! results.closeModal ) {
			widgetoptsSettingsModule.showMessages( results.messages, results.module, 'open' );
			$container.find( '.widgetopts-module-settings-content-container:visible' ).animate( {'scrollTop': 0}, 'fast' );

		} else {
			widgetoptsSettingsModule.showMessages( results.messages, results.module, 'closed' );
			$container.find( '.widgetopts-module-settings-content-container:visible' ).scrollTop( 0 );
			widgetoptsSettingsModule.closeModal();
		}
	},

	clearMessages: function() {
		jQuery( '#widgetopts-settings-messages-container, .widgetopts-module-messages-container' ).empty();
	},

	showMessages: function( messages, module, containerStatus ) {
		jQuery.each( messages, function( index, message ) {
			widgetoptsSettingsModule.showMessage( message, module, containerStatus );
		} );
	},

	showMessage: function( message, module, containerStatus ) {
		var view = 'grid';

		if ( 'closed' !== containerStatus && 'open' !== containerStatus ) {
			containerStatus = 'closed';
		}

		if ( 'string' !== typeof module ) {
			module = '';
		}

		if ( 'closed' === containerStatus || '' === module ) {
			var container = jQuery( '#widgetopts-settings-messages-container' );

			setTimeout( function() {
				container.removeClass( 'visible' );
				setTimeout( function() {
					container.find( 'div' ).remove();
				}, 500 );
			}, 4000 );
		} else {
			var container = jQuery( '#widgetopts-module-card-' + module + ' .widgetopts-module-messages-container' );
		}

		container.append( '<div class="updated fade"><p><strong>' + message + '</strong></p></div>' ).addClass( 'visible' );
	},


	ajaxRequest: function( module, method, data, callback ) {
		var postData = {
			'action': widgetopts.ajax_action,
			'nonce':  widgetopts.ajax_nonce,
			'module': module,
			'method': method,
			'data':   data,
		};

		jQuery.post( ajaxurl, postData )
			.always(function( a, status, b ) {
				widgetoptsSettingsModule.processAjaxResponse( a, status, b, module, method, data, callback );
			});
	},

	processAjaxResponse: function( a, status, b, module, method, data, callback ) {
		var results = {
			'module':        module,
			'method':        method,
			'data':          data,
			'status':        status,
			'license_status': null,
			'jqxhr':         null,
			'success':       false,
			'response':      null,
			'errors':        [],
			'messages':      [],
			'functionCalls': [],
			'redirect':      false,
			'closeModal':    true
		};
		// console.log( a );

		a = jQuery.parseJSON( a  );

		if ( 'WIDGETOPTS_Response' === a.source && 'undefined' !== a.response ) {
			// Successful response with a valid format.
			results.jqxhr = b;
			results.success = a.success;
			results.response = a.response;
			results.errors = a.errors;
			results.messages = a.messages;
			results.functionCalls = a.functionCalls;
			results.redirect = a.redirect;
			results.closeModal = a.closeModal;

			if( typeof results.license_status != 'undefined' ){
				results.license_status = a.license_status;
			}
		}

		if ( 'function' === typeof callback ) {
			callback( results );
		} else if ( 'function' === typeof console.log ) {
			console.log( 'ERROR: Unable to handle settings AJAX request due to an invalid callback:', callback, {'data': postData, 'results': results} );
		}

	}
}

jQuery(document).ready(function() {
	widgetoptsSettingsModule.init();
});
