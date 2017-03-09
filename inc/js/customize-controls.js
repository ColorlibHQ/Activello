( function( api ) {

	// Extends our custom "activello-documentation" section.
	api.sectionConstructor['activello-documentation'] = api.Section.extend( {

		// No events for this type of section.
		attachEvents: function () {},

		// Always make the section active.
		isContextuallyActive: function () {
			return true;
		}
	} );

} )( wp.customize );