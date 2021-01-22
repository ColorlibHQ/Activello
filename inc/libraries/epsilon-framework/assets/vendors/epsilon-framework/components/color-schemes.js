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