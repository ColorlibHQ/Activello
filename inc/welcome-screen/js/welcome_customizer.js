( function( api ) {

	// Extends our custom "activello-pro-section" section.
	api.sectionConstructor['activello-recomended-section'] = api.Section.extend( {

		// No events for this type of section.
		attachEvents: function () {},

		// Always make the section active.
		isContextuallyActive: function () {
			return true;
		}
	} );

} )( wp.customize );