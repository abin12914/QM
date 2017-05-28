$(function () {
    dismissAlert();
    
    // initialize the calendar
    $('#calendar').fullCalendar({
        height: 600,
        header: {
            left: 'prev',
            center: 'title',
            right: 'next'
      }
    });
});