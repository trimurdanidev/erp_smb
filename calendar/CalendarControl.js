function positionInfo(object) {

  var p_elm = object;

  this.getElementLeft = getElementLeft;
  function getElementLeft() {
    var x = 0;
    var elm;
    if(typeof(p_elm) == "object"){
      elm = p_elm;
    } else {
      elm = document.getElementById(p_elm);
    }
    while (elm != null) {
      if(elm.style.position == 'relative') {
        break;
      }
      else {
        x += elm.offsetLeft;
        elm = elm.offsetParent;
      }
    }
    return parseInt(x);
  }

  this.getElementWidth = getElementWidth;
  function getElementWidth(){
    var elm;
    if(typeof(p_elm) == "object"){
      elm = p_elm;
    } else {
      elm = document.getElementById(p_elm);
    }
    return parseInt(elm.offsetWidth);
  }

  this.getElementRight = getElementRight;
  function getElementRight(){
    return getElementLeft(p_elm) + getElementWidth(p_elm);
  }

  this.getElementTop = getElementTop;
  function getElementTop() {
    var y = 0;
    var elm;
    if(typeof(p_elm) == "object"){
      elm = p_elm;
    } else {
      elm = document.getElementById(p_elm);
    }
    while (elm != null) {
      if(elm.style.position == 'relative') {
        break;
      }
      else {
        y+= elm.offsetTop;
        elm = elm.offsetParent;
      }
    }
    return parseInt(y);
  }

  this.getElementHeight = getElementHeight;
  function getElementHeight(){
    var elm;
    if(typeof(p_elm) == "object"){
      elm = p_elm;
    } else {
      elm = document.getElementById(p_elm);
    }
    return parseInt(elm.offsetHeight);
  }

  this.getElementBottom = getElementBottom;
  function getElementBottom(){
    return getElementTop(p_elm) + getElementHeight(p_elm);
  }
}

function CalendarControl() {

  var calendarId = 'CalendarControl';
  var currentYear = 0;
  var currentMonth = 0;
  var currentDay = 0;

  var selectedYear = 0;
  var selectedMonth = 0;
  var selectedDay = 0;

  var months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
  var dateField = null;

  function getProperty(p_property){
    var p_elm = calendarId;
    var elm = null;

    if(typeof(p_elm) == "object"){
      elm = p_elm;
    } else {
      elm = document.getElementById(p_elm);
    }
    if (elm != null){
      if(elm.style){
        elm = elm.style;
        if(elm[p_property]){
          return elm[p_property];
        } else {
          return null;
        }
      } else {
        return null;
      }
    }
  }

  function setElementProperty(p_property, p_value, p_elmId){
    var p_elm = p_elmId;
    var elm = null;

    if(typeof(p_elm) == "object"){
      elm = p_elm;
    } else {
      elm = document.getElementById(p_elm);
    }
    if((elm != null) && (elm.style != null)){
      elm = elm.style;
      elm[ p_property ] = p_value;
    }
  }

  function setProperty(p_property, p_value) {
    setElementProperty(p_property, p_value, calendarId);
  }

  function getDaysInMonth(year, month) {
    return [31,((!(year % 4 ) && ( (year % 100 ) || !( year % 400 ) ))?29:28),31,30,31,30,31,31,30,31,30,31][month-1];
  }

  function getDayOfWeek(year, month, day) {
    var date = new Date(year,month-1,day)
    return date.getDay();
  }

  this.clearDate = clearDate;
  function clearDate() {
    dateField.value = '';
    hide();
  }

  this.setDate = setDate;
  function setDate(year, month, day) {
    if (dateField) {
      if (month < 10) {month = "0" + month;}
      if (day < 10) {day = "0" + day;}

      var dateString = year+"-"+month+"-"+day;
      dateField.value = dateString;
      hide();
    }
    return;
  }

  this.changeMonth = changeMonth;
  function changeMonth(change) {
    currentMonth += change;
    currentDay = 0;
    if(currentMonth > 12) {
      currentMonth = 1;
      currentYear++;
    } else if(currentMonth < 1) {
      currentMonth = 12;
      currentYear--;
    }

    calendar = document.getElementById(calendarId);
    calendar.innerHTML = calendarDrawTable();
  }

  this.changeYear = changeYear;
  function changeYear(change) {
    currentYear += change;
    currentDay = 0;
    calendar = document.getElementById(calendarId);
    calendar.innerHTML = calendarDrawTable();
  }

  function getCurrentYear() {
    var year = new Date().getYear();
    if(year < 1900) year += 1900;
    return year;
  }

  function getCurrentMonth() {
    return new Date().getMonth() + 1;
  } 

  function getCurrentDay() {
    return new Date().getDate();
  }

  function calendarDrawTable() {

    var dayOfMonth = 1;
    var validDay = 0;
    var startDayOfWeek = getDayOfWeek(currentYear, currentMonth, dayOfMonth);
    var daysInMonth = getDaysInMonth(currentYear, currentMonth);
    var css_class = null; //CSS class for each day

    var table = "<table width='100px' height='100px' cellspacing='0' cellpadding='0' border='0' style='  font-family: arial, verdana, helvetica,sans-serif;font-size: 8pt;border-left: 1px solid #336;border-right: 1px solid #336;'>";
    table = table + "<tr style='background-color: #336;'>";
    table = table + "  <td colspan='2' style='text-align: left;'><a href='javascript:changeCalendarControlMonth(-1);'  style='padding: 3px; font-weight: normal;text-decoration: none;color: #FFF;padding: 1px;'>&lt;</a> <a href='javascript:changeCalendarControlYear(-1);'  style='padding: 3px; font-weight: normal;text-decoration: none;color: #FFF;padding: 1px;'>&laquo;</a></td>";
    table = table + "  <td colspan='3' style='text-align: center;font-weight: bold;color: #FFF;'>" + months[currentMonth-1] + "<br>" + currentYear + "</td>";
    table = table + "  <td colspan='2' style='text-align: right;'><a href='javascript:changeCalendarControlYear(1);'  style='padding: 3px; font-weight: normal;text-decoration: none;color: #FFF;padding: 1px;'>&raquo;</a> <a href='javascript:changeCalendarControlMonth(1);'  style='padding: 3px; font-weight: normal;text-decoration: none;color: #FFF;padding: 1px;'>&gt;</a></td>";
    table = table + "</tr>";
    table = table + "<tr><th >S</th><th>M</th><th >T</th><th >W</th><th >T</th><th >F</th><th >S</th></tr>";

    for(var week=0; week < 6; week++) {
      table = table + "<tr>";
      for(var dayOfWeek=0; dayOfWeek < 7; dayOfWeek++) {
        if(week == 0 && startDayOfWeek == dayOfWeek) {
          validDay = 1;
        } else if (validDay == 1 && dayOfMonth > daysInMonth) {
          validDay = 0;
        }

        if(validDay) {
          if (dayOfMonth == selectedDay && currentYear == selectedYear && currentMonth == selectedMonth) {
            css_class = 'border: 1px solid #339;background-color: #336;color: #FFF;';
          } else if (dayOfWeek == 0 || dayOfWeek == 6) {
            css_class = 'background-color: #FFC;color: #000;';
          } else {
            css_class = 'background-color: #DDD;color: #000;';
          }

          table = table + "<td style='"+ css_class + "'><a style='"+css_class+"' href=\"javascript:setCalendarControlDate("+currentYear+","+currentMonth+","+dayOfMonth+")\">"+dayOfMonth+"</a></td>";
          dayOfMonth++;
        } else {
          table = table + "<td style='background-color: #CCC;border: 1px solid #FFF;'>&nbsp;</td>";
        }
      }
      table = table + "</tr>";
    }

    table = table + "<tr style='background-color: #CCC;border: 1px solid #FFF;'><th colspan='7' style='padding: 3px; ' ><a href='javascript:clearCalendarControl();' style='padding: 3px; font-weight: normal;text-decoration: none;padding: 1px;'>Clear</a> | <a href='javascript:hideCalendarControl();' style='padding: 3px; font-weight: normal;text-decoration: none;padding: 1px;'>Close</a></td></tr>";
    table = table + "</table>";

    return table;
  }

  this.show = show;
  function show(field, position) {
    calendarId = position;
    
    can_hide = 0;
  
    // If the calendar is visible and associated with
    // this field do not do anything.
    if (dateField == field) {
      return;
    } else {
      dateField = field;
    }

    if(dateField) {
      try {
        var dateString = new String(dateField.value);
        var dateParts = dateString.split("-");
        
        selectedMonth = parseInt(dateParts[1],10);
        selectedDay = parseInt(dateParts[2],10);
        selectedYear = parseInt(dateParts[0],10);
      } catch(e) {}
    }

    if (!(selectedYear && selectedMonth && selectedDay)) {
      selectedMonth = getCurrentMonth();
      selectedDay = getCurrentDay();
      selectedYear = getCurrentYear();
    }

    currentMonth = selectedMonth;
    currentDay = selectedDay;
    currentYear = selectedYear;

    if(document.getElementById){

      calendar = document.getElementById(calendarId);
      calendar.innerHTML = calendarDrawTable(currentYear, currentMonth);

      setProperty('display', 'block');

      var fieldPos = new positionInfo(dateField);
      var calendarPos = new positionInfo(calendarId);

      var x = fieldPos.getElementLeft();
      var y = fieldPos.getElementBottom();

      setProperty('left', x + "px");
      setProperty('top', y + "px");
 
      if (document.all) {
        setElementProperty('display', 'block', 'CalendarControlIFrame');
        setElementProperty('left', x + "px", 'CalendarControlIFrame');
        setElementProperty('top', y + "px", 'CalendarControlIFrame');
        setElementProperty('width', calendarPos.getElementWidth() + "px", 'CalendarControlIFrame');
        setElementProperty('height', calendarPos.getElementHeight() + "px", 'CalendarControlIFrame');
      }
    }
  }

  this.hide = hide;
  function hide() {
    if(dateField) {
      setProperty('display', 'none');
      setElementProperty('display', 'none', 'CalendarControlIFrame');
      dateField = null;
    }
  }

  this.visible = visible;
  function visible() {
    return dateField
  }

  this.can_hide = can_hide;
  var can_hide = 0;
}

var calendarControl = new CalendarControl();

function showCalendarControl(textField, position) {
  textField.onkeypress = hideCalendarControl;
  calendarControl.show(textField ,position);
}

function clearCalendarControl() {
  calendarControl.clearDate();
}

function hideCalendarControl() {
  if (calendarControl.visible()) {
    calendarControl.hide();
  }
}

function setCalendarControlDate(year, month, day) {
  calendarControl.setDate(year, month, day);
}

function changeCalendarControlYear(change) {
  calendarControl.changeYear(change);
}

function changeCalendarControlMonth(change) {
  calendarControl.changeMonth(change);
}


