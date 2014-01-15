$(function() {
		$( "[name='birthdate']" ).datepicker({ 
			minDate: "-50Y", 
			maxDate: "-12Y",
			 changeMonth: true,
			 changeYear: true,
			 dateFormat:'yy-mm-dd',
			 yearRange: '1950:2014'});
	});