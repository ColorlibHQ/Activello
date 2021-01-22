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