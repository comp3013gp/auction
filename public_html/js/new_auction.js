$(document).ready(function() {
  var date_time = new Date();
  var current_year = date_time.getFullYear();
  var current_month = date_time.getMonth();
  var current_day = date_time.getDate();
  var current_time = date_time.getHours();
  var i;
  var months = new Array();
    months[0] = "January";
    months[1] = "February";
    months[2] = "March";
    months[3] = "April";
    months[4] = "May";
    months[5] = "June";
    months[6] = "July";
    months[7] = "August";
    months[8] = "September";
    months[9] = "October";
    months[10] = "November";
    months[11] = "December";

  function cal_days(month, year) {
    if (month == 2) {
      if (year % 4 != 0) {
        return 28;
      } else if (year % 100 == 0) {
        if (year % 400 == 0) {
          return 29;
        } else {
          return 28;
        }
      } else {
        return 29;
      }
    } else {
      if (month < 8) {
        if (month % 2 == 0) {
          return 30;
        } else {
          return 31;
        }
      } else {
        if (month % 2 == 0) {
          return 31;
        } else {
          return 30;
        }
      }
    }
  }

  for (i = current_year; i < current_year + 3; i++) {
    $("select#end-year").append("<option value='" + i + "'>" + i + "</option>");
  }

  $("select#end-year").change(function() {
    if ($("select#end-year option:selected").val() == current_year) {
      $("select#end-month option").not(".default-op").remove();
      $("select#end-day option").not(".default-op").remove();
      $("select#end-time option").not(".default-op").remove();
      for (i = current_month; i < 12; i++) {
        var val = i + 1;
        if (val < 10) {
          $("select#end-month").append("<option value='0" + val + "'>" + months[i] + "</option>");
        } else {
          $("select#end-month").append("<option value='" + val + "'>" + months[i] + "</option>");
        }
      }
    } else if ($("select#end-year option:selected").text() == "Year"){
      $("select#end-month option").not(".default-op").remove();
      $("select#end-day option").not(".default-op").remove();
      $("select#end-time option").not(".default-op").remove();
    } else {
      $("select#end-month option").not(".default-op").remove();
      $("select#end-day option").not(".default-op").remove();
      $("select#end-time option").not(".default-op").remove();
      for (i = 0; i < 12; i++) {
        var val = i + 1;
        if (val < 10) {
          $("select#end-month").append("<option value='0" + val + "'>" + months[i] + "</option>");
        } else {
          $("select#end-month").append("<option value='" + val + "'>" + months[i] + "</option>");
        }
      }
    }
  });

  $("select#end-month").change(function() {
    if ($("select#end-year option:selected").text() == "Year"
          || $("select#end-month option:selected").text() == "Month"){
      $("select#end-day option").not(".default-op").remove();
      $("select#end-time option").not(".default-op").remove();
    } else {
      var chosen_year = $("select#end-year option:selected").val();
      var chosen_month = $("select#end-month option:selected").val();
      var max_day = cal_days(chosen_month, chosen_year);
      if ($("select#end-year option:selected").val() == current_year
            && $("select#end-month option:selected").val() == current_month + 1) {
        $("select#end-day option").not(".default-op").remove();
        $("select#end-time option").not(".default-op").remove();
        for (i = current_day; i < max_day + 1; i++) {
          if (i < 10) {
            $("select#end-day").append("<option value='0" + i + "'>" + i + "</option>");
          } else {
            $("select#end-day").append("<option value='" + i + "'>" + i + "</option>");
          }
        }
      } else {
        $("select#end-day option").not(".default-op").remove();
        $("select#end-time option").not(".default-op").remove();
        for (i = 1; i < max_day + 1; i++) {
          if (i < 10) {
            $("select#end-day").append("<option value='0" + i + "'>" + i + "</option>");
          } else {
            $("select#end-day").append("<option value='" + i + "'>" + i + "</option>");
          }
        }
      }
    }
  });

  $("select#end-day").change(function() {
      if ($("select#end-year option:selected").val() == current_year
            && $("select#end-month option:selected").val() == current_month + 1
            && $("select#end-day option:selected").val() == current_day) {
        $("select#end-time option").not(".default-op").remove();
        for (i = current_time + 1; i < 24; i++) {
          if (i < 10) {
            $("select#end-time").append("<option value='0" + i + ":00:00'>0" + i + ":00</option>");
          } else {
            $("select#end-time").append("<option value='" + i + ":00:00'>" + i + ":00</option>");
          }
        }
      } else {
        $("select#end-time option").not(".default-op").remove();
        for (i = 0; i < 24; i++) {
          if (i < 10) {
            $("select#end-time").append("<option value='0" + i + ":00:00'>0" + i + ":00</option>");
          } else {
            $("select#end-time").append("<option value='" + i + ":00:00'>" + i + ":00</option>");
          }
        }
      }
  });
});
