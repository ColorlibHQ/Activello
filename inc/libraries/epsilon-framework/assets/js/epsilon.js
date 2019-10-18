/*
 EpsilonFramework Object
 */

var EpsilonFramework = 'undefined' === typeof( EpsilonFramework ) ? {} : EpsilonFramework;

/**
 * Color scheme generator
 */
EpsilonFramework.colorSchemes = function() {
  /**
   * Set variables
   */
  var context = jQuery( '.epsilon-color-scheme' ), options, input, json, api,
      colorSettings = [], css = {};

  if ( ! context.length ) {
    return;
  }

  options = context.find( '.epsilon-color-scheme-option' );
  input = context.parent().find( '.epsilon-color-scheme-input' );
  json = jQuery.parseJSON( options.first().find( 'input' ).val() );
  api = wp.customize;
  colorSettings = [];
  css = {
    'action': 'epsilon_generate_color_scheme_css',
    'class': 'Epsilon_Color_Scheme',
    'id': '',
    'data': {}
  };

  jQuery.each( json, function( index, value ) {
    colorSettings.push( index );
  } );

  function updateCSS() {
    _.each( colorSettings, function( setting ) {
      css.data[ setting ] = api( setting )();
    } );
    api.previewer.send( 'update-inline-css', css );
  }

  _.each( colorSettings, function( setting ) {
    api( setting, function( setting ) {
      setting.bind( updateCSS );
    } );
  } );

  /**
   * On clicking a color scheme, update the color pickers
   */
  jQuery( '.epsilon-color-scheme-option' ).on( 'click', function() {
    var val = jQuery( this ).attr( 'data-color-id' ),
        json = jQuery.parseJSON( jQuery( this ).find( 'input' ).val() );

    /**
     * Find the customizer options
     */
    jQuery.each( json, function( index, value ) {
      colorSettings.push( index );
      /**
       * Set values
       */
      wp.customize( index ).set( value );
    } );

    /**
     * Remove the selected class from siblings
     */
    jQuery( this ).
        siblings( '.epsilon-color-scheme-option' ).
        removeClass( 'selected' );
    /**
     * Make active the current selection
     */
    jQuery( this ).addClass( 'selected' );
    /**
     * Trigger change
     */
    input.val( val ).change();

    _.each( colorSettings, function( setting ) {
      api( setting, function( setting ) {
        setting.bind( updateCSS() );
      } );
    } );
  } );
};
EpsilonFramework.rangeSliders = function( selector ) {
  var context = jQuery( selector ),
      sliders = context.find( '.slider-container' ),
      slider, input, inputId, id, min, max, step;

  jQuery.each( sliders, function() {
    var slider = jQuery( this ).find( '.ss-slider' ),
        input = jQuery( this ).find( '.rl-slider' ),
        inputId = input.attr( 'id' ),
        id = slider.attr( 'id' ),
        min = jQuery( '#' + id ).attr( 'data-attr-min' ),
        max = jQuery( '#' + id ).attr( 'data-attr-max' ),
        step = jQuery( '#' + id ).attr( 'data-attr-step' );

    jQuery( '#' + id ).slider( {
      value: jQuery( '#' + inputId ).attr( 'value' ),
      range: 'min',
      min: parseFloat( min ),
      max: parseFloat( max ),
      step: parseFloat( step ),
      /**
       * Removed Change event because server was flooded with requests from
       * javascript, sending changesets on each increment.
       *
       * @param event
       * @param ui
       */
      slide: function( event, ui ) {
        jQuery( '#' + inputId ).attr( 'value', ui.value );
      },
      /**
       * Bind the change event to the "actual" stop
       * @param event
       * @param ui
       */
      stop: function( event, ui ) {
        jQuery( '#' + inputId ).trigger( 'change' );
      }
    } );

    jQuery( input ).on( 'focus', function() {
      jQuery( this ).blur();
    } );

    jQuery( '#' + inputId ).attr( 'value', ( jQuery( '#' + id ).slider( 'value' ) ) );
    jQuery( '#' + inputId ).on( 'change', function() {
      jQuery( '#' + id ).slider( {
        value: jQuery( this ).val()
      } );
    } );
  } );
};
/**
 * Recommended action section scripting
 *
 * @type {{_init: _init, dismissActions: dismissActions, dismissPlugins:
   *     dismissPlugins}}
 */
/*jshint -W065 */
EpsilonFramework.recommendedActions = {
  /**
   * Initiate the click actions
   *
   * @private
   */
  _init: function() {
    var context = jQuery( '.control-section-epsilon-section-recommended-actions' ),
        dismissPlugin = context.find( '.epsilon-recommended-plugin-button' ),
        dismissAction = context.find( '.epsilon-dismiss-required-action' );

    /**
     * Dismiss actions
     */
    this.dismissActions( dismissAction );
    /**
     * Dismiss plugins
     */
    this.dismissPlugins( dismissPlugin );
  },

  /**
   * Dismiss actions function, hides the container and shows the next one
   * while changing the INDEX in the title
   * @param selectors
   */
  dismissActions: function( selectors ) {
    selectors.on( 'click', function() {
      /**
       * During ajax, we lose scope - so declare "self"
       * @type {*}
       */
      var self = jQuery( this ),
          /**
           * Get the container
           */
          container = self.parents(
              '.epsilon-recommended-actions-container' ),
          /**
           * Get the current index
           *
           * @type {Number}
           */
          index = parseInt( container.attr( 'data-index' ) ),
          /**
           * Get the title
           *
           * @type {*}
           */
          title = container.parents(
              '.control-section-epsilon-section-recommended-actions' ).
              find( 'h3' ),
          /**
           * Get the indew from the notice
           *
           * @type {*}
           */
          notice = title.find( '.epsilon-actions-count > .current-index' ),
          /**
           * Get the total
           *
           * @type {Number}
           */
          total = parseInt( notice.attr( 'data-total' ) ),
          /**
           * Get the next element ( this will be shown next )
           */
          next = container.next(),
          /**
           * Create the args object for the AJAX call
           *
           * action [ Class, Method Name ]
           * args [ parameters to be sent to method ]
           *
           * @type {{action: [*], args: {id: *, option: *}}}
           */
          args = {
            'action': [ 'Epsilon_Framework', 'dismiss_required_action' ],
            'args': {
              'id': jQuery( this ).attr( 'id' ),
              'option': jQuery( this ).attr( 'data-option' )
            }
          },
          replace, plugins, replaceText;

      /**
       * Initiate the AJAX function
       *
       * Note that the Epsilon_Framework class, has the following method :
       *
       * public function epsilon_framework_ajax_action(){};
       *
       * which is used as a proxy to gather jQuery_POST data, verify it
       * and call the needed function, in this case :
       * Epsilon_Framework::dismiss_required_action()
       *
       */
      jQuery.ajax( {
        type: 'POST',
        data: { action: 'epsilon_framework_ajax_action', args: args },
        dataType: 'json',
        url: WPUrls.ajaxurl,
        success: function( data ) {
          /**
           * In case everything is ok, we start changing things
           */
          if ( data.status && 'ok' === data.message ) {
            /**
             * If it's the last element, show plugins
             */

            if ( total <= index ) {
              replace = title.find( '.section-title' );
              plugins = jQuery( '.epsilon-recommended-plugins' );
              replaceText = replace.attr( 'data-social' );

              if ( plugins.length ) {
                replaceText = replace.attr( 'data-plugin_text' );
              }

              title.find( '.epsilon-actions-count' ).remove();
              replace.text( replaceText );

            }
            /**
             * Else, just change the index
             */
            else {
              notice.text( index + 1 );
            }

            /**
             * Fade the current element and show the next one.
             * We don't need to remove it at this time. Leave it to for
             * server side
             */
            container.fadeOut( '200', function() {
              next.css( { opacity: 1, height: 'initial' } ).fadeIn( '200' );
            } );
          }
        },

        /**
         * Throw errors
         *
         * @param jqXHR
         * @param textStatus
         * @param errorThrown
         */
        error: function( jqXHR, textStatus, errorThrown ) {
          console.log( jqXHR + ' :: ' + textStatus + ' :: ' + errorThrown );
        }
      } );
    } );
  },

  /**
   * Dismiss plugins function, hides the container and shows the next one
   * while changing the INDEX in the title
   * @param selectors
   */
  dismissPlugins: function( selectors ) {
    selectors.on( 'click', function() {
      /**
       * During ajax, we lose scope - so declare "self"
       * @type {*}
       */
      var self = jQuery( this ),
          /**
           * Get the container
           */
          container = self.parents( '.epsilon-recommended-plugins' ),
          /**
           * Get the next element (this will be shown next)
           */
          next = container.next(),
          /**
           * Get the title
           *
           * @type {*}
           */
          title = container.parents(
              '.control-section-epsilon-section-recommended-actions' ).
              find( 'h3' ),
          /**
           * Create the args object for the AJAX call
           *
           * action [ Class, Method Name ]
           * args [ parameters to be sent to method ]
           *
           * @type {{action: [*], args: {id: *, option: *}}}
           */
          args = {
            'action': [ 'Epsilon_Framework', 'dismiss_required_action' ],
            'args': {
              'id': jQuery( this ).attr( 'id' ),
              'option': jQuery( this ).attr( 'data-option' )
            }
          },
          replace, replaceText;

      jQuery.ajax( {
        type: 'POST',
        data: { action: 'epsilon_framework_ajax_action', args: args },
        dataType: 'json',
        url: WPUrls.ajaxurl,
        success: function( data ) {
          /**
           * In case everything is ok, we start changing things
           */
          if ( data.status && 'ok' === data.message ) {
            /**
             * Fade the current element and show the next one.
             * We don't need to remove it at this time. Leave it to for
             * server side
             */
            container.fadeOut( '200', function() {
              if ( next.is( 'p' ) ) {
                replace = title.find( '.section-title' );
                replaceText = replace.attr( 'data-social' );

                replace.text( replaceText );
              }
              next.css( { opacity: 1, height: 'initial' } ).fadeIn( '200' );
            } );
          }
        },

        /**
         * Throw errors
         *
         * @param jqXHR
         * @param textStatus
         * @param errorThrown
         */
        error: function( jqXHR, textStatus, errorThrown ) {
          console.log( jqXHR + ' :: ' + textStatus + ' :: ' + errorThrown );
        }
      } );
    } );
  }
};
EpsilonFramework.typography = {
  /**
   * Selectize instance
   */
  _selectize: null,

  /**
   * K/V Pair
   */
  _linkedFonts: {},

  /**
   * Initiate function
   * @private
   */
  _init: function() {
    var selector = jQuery( '.epsilon-typography-container' ),
        self = this;

    if ( selector.length ) {
      jQuery.each( selector, function() {
        var container = jQuery( this ),
            uniqueId = container.attr( 'data-unique-id' ),
            selects = container.find( 'select' ),
            inputs = container.find( '.epsilon-typography-input' );

        /**
         * Instantiate the selectize javascript plugin
         * and the input type number
         */
        try {
          self._selectize = selects.selectize();
        }
        catch ( err ) {
          /**
           * In case the selectize plugin is not loaded, raise an error
           */
          console.warn( 'selectize not yet loaded' );
        }
        /**
         * On triggering the change event, create a json with the values and
         * send it to the preview window
         */
        inputs.on( 'change', function() {
          var val = EpsilonFramework.typography._parseJson( inputs,
              uniqueId );
          jQuery( '#hidden_input_' + uniqueId ).val( val ).trigger( 'change' );
        } );
      } );

      /**
       * Great use of the EpsilonFramework, ahoy!
       */
      EpsilonFramework.rangeSliders( '.epsilon-typography-container' );

      /**
       * Reset button
       */
      jQuery( '.epsilon-typography-default' ).on( 'click', function( e ) {
        e.preventDefault();
        EpsilonFramework.typography._resetDefault( jQuery( this ) );
      } );

      /**
       * On clicking the advanced options toggler,
       */
      jQuery( '.epsilon-typography-advanced-options-toggler' ).
          on( 'click', function( e ) {
            var toggle = jQuery( this ).attr( 'data-toggle' );
            e.preventDefault();
            jQuery( this ).
                toggleClass( 'active' ).
                parent().
                toggleClass( 'active' );
            jQuery( '#' + toggle ).slideToggle().addClass( 'active' );
          } );
    }
  },

  /**
   * Reset defaults
   *
   * @param element
   * @private
   */
  _resetDefault: function( element ) {
    var container = jQuery( element ).parent(),
        uniqueId = container.attr( 'data-unique-id' ),
        selects = container.find( 'select' ),
        inputs = container.find( 'inputs' ),
        val;

    var fontFamily = selects[ 0 ].selectize;

    var object = {
          'action': 'epsilon_generate_typography_css',
          'class': 'Epsilon_Typography',
          'id': uniqueId,
          'data': {
            'selectors': jQuery( '#selectors_' + uniqueId ).val(),
            'json': {}
          }
        },
        api = wp.customize;

    fontFamily.setValue( 'default_font' );

    if ( jQuery( '#' + uniqueId + '-font-size' ).length ) {
      val = jQuery( '#' + uniqueId + '-font-size' ).
          attr( 'data-default-font-size' );

      jQuery( '#' + uniqueId + '-font-size' ).
          val( val ).
          trigger( 'change' ).
          trigger( 'blur' );
      object.data.json[ 'font-size' ] = '';
    }

    if ( jQuery( '#' + uniqueId + '-line-height' ).length ) {
      val = jQuery( '#' + uniqueId + '-line-height' ).
          attr( 'data-default-line-height' );

      jQuery( '#' + uniqueId + '-line-height' ).
          val( val ).
          trigger( 'change' ).
          trigger( 'blur' );
      object.data.json[ 'line-height' ] = '';
    }

    if ( jQuery( '#' + uniqueId + '-letter-spacing' ).length ) {
      val = jQuery( '#' + uniqueId + '-letter-spacing' ).
          attr( 'data-default-letter-spacing' );

      jQuery( '#' + uniqueId + '-letter-spacing' ).
          val( val ).
          trigger( 'change' ).
          trigger( 'blur' );
      object.data.json[ 'letter-spacing' ] = '';
    }

    object.data.json[ 'font-family' ] = 'default_font';
    object.data.json[ 'font-weight' ] = '';
    object.data.json[ 'font-style' ] = '';

    api.previewer.send( 'update-inline-css', object );
  },

  /**
   * Parse/create the json and send it to the preview window
   *
   * @param inputs
   * @param id
   * @private
   */
  _parseJson: function( inputs, id ) {
    var object = {
          'action': 'epsilon_generate_typography_css',
          'class': 'Epsilon_Typography',
          'id': id,
          'data': {
            'selectors': jQuery( '#selectors_' + id ).val(),
            'json': {}
          }
        },
        api = wp.customize;

    jQuery.each( inputs, function( index, value ) {
      var key = jQuery( value ).attr( 'id' ),
          replace = id + '-',
          type = jQuery( this ).attr( 'type' );
      key = key.replace( replace, '' );

      if ( 'checkbox' === type ) {
        object.data.json[ key ] = jQuery( this ).prop( 'checked' ) ? jQuery( value ).
            val() : '';
      } else {
        object.data.json[ key ] = jQuery( value ).val();
      }

    } );

    api.previewer.send( 'update-inline-css', object );
    return JSON.stringify( object.data );
  }
};
/**
 *
 * File epsilon.js.
 *
 * Epsilon Framework Initiator
 */

/**
 * Load the range sliders for the widget updates
 */
jQuery( document ).on( 'widget-updated widget-added', function( a, selector ) {
  if ( jQuery().slider ) {
    EpsilonFramework.rangeSliders( selector );
  }
} );

if ( 'undefined' !== typeof( wp ) ) {
  if ( 'undefined' !== typeof( wp.customize ) ) {
    wp.customize.bind( 'ready', function() {
      EpsilonFramework.typography._init();
      EpsilonFramework.colorSchemes();
      EpsilonFramework.recommendedActions._init();
    } );

    wp.customize.sectionConstructor[ 'epsilon-section-pro' ] = wp.customize.Section.extend(
        {
          attachEvents: function() {
          },
          isContextuallyActive: function() {
            return true;
          }
        } );

    wp.customize.sectionConstructor[ 'epsilon-section-recommended-actions' ] = wp.customize.Section.extend(
        {
          attachEvents: function() {
          },
          isContextuallyActive: function() {
            return true;
          }
        } );
  }
}
