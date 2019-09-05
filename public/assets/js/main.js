var left = $("#q-left");
var right = $("#q-right");

var API = {
  query: function (uri, method, data, callback) {
    var url = API.url(uri);

    var xhr = $.ajax({
      type: method,
      url: url,
      data: data,
      timeout: 5000,
      dataType: 'json'
    });

    xhr.done(function(response) {
      if (typeof callback === 'function') {
        callback(response);
      }
    });

    xhr.fail(function(response) {
      if (typeof xhr.responseJSON === 'undefined') {
        return UI.error('undefined ajax error', 500);
      }
    });
  },

  url: function (uri) {
    // NOTE: define api url here
    return 'https://api.thisorthat.ru' + uri;
  }
}

var UI = {
  error: function (message, code) {
    console.log(message);
  },

  versus: function (show) {
    var obj = $("#ui-versus");
    var cl = 'onload';

    if (typeof show === 'undefined')
      return obj.toggleClass(cl);

    return show ? obj.addClass(cl) : obj.removeClass(cl);
  },

  result: function (id, sel, result) {
    var vote = result.vote;
    var count = result.count;

    var data = [{
      value: vote.r,
      color: "#5F4886",
    }, {
      value: vote.l,
      color: "#D54B33",
    }];

    var views = {};
    views[id] = sel;

    var ctx = document.getElementById("ui-result").getContext("2d");
    window.uiChart = new Chart(ctx).Doughnut(data, {
      responsive: true,
      animationEasing: "easeOutCirc",
      animationSteps: 50,
      segmentShowStroke: false,
      showTooltips: false,
    });

    var countdown = function (arr, more) {
      var dfd = new jQuery.Deferred();

      $("#ui-questions").addClass('result');

      var timer = function (el, v, c, is) {
        for (var i = 0; i < v; ++i) {
          setTimeout(function () {
            el.find("h3 > b").html(++c);
            if (c === v - 1 && is)
              dfd.resolve();
          }, 20 * i)
        }
      }

      $.each(arr, function (i, v) {
        timer(window[i], v, 0, i === more);
      });

      return dfd.promise();
    }

    var numbers = function (arr, sel) {
      $.each(arr, function (i, v) {
        var cs = [];
        if (i === sel)
          cs = [' пользователь ответил как вы', ' пользователя ответили как вы', ' пользователей ответили как вы'];
        else
          cs = [' пользователь ответил иначе', ' пользователя ответили иначе', ' пользователей ответили иначе'];

        $("#q-" + i).find("h4").html(Application.correct_number(v, cs));
      });

    }

    numbers(count, sel);

    $(".question").off('click');
    $(".question-" + sel).addClass('selected');

    Application.add_view(views, function (){
      $.when(countdown({
        left: vote.l,
        right: vote.r
      }, vote.l >= vote.r ? 'left' : 'right')).then(function (status) {
        $(".question").on('click', function () {
          UI.change();
        });
      });
    });
  },

  change: function (data) {
    this.versus(true);

    Application.reinit();
  },

  questions: function (response) {
    var q = response[0],
      obj = $("#ui-questions"),
      vote = {},
      result = {},
      id = q['item_id'];


    obj.data('id', id);

    vote.l = parseInt(q.first_vote) || 0;
    vote.r = parseInt(q.last_vote) || 0;

    vote.l = (vote.l !== vote.r) ? Math.round(100 / (vote.l + vote.r) * vote.l) : 50;
    vote.r = 100 - vote.l;

    left.find("h2").html(q.first_text);
    right.find("h2").html(q.last_text);

    UI.versus(false);

    result.count = {
      left: q.first_vote,
      right: q.last_vote
    };

    result.vote = vote;

    $(".question").off('click').on('click', function () {
      var sel = $(this).attr('class').match(/question-(left|right)/)[1];

      UI.result(id, sel, result);
    });
  }
}

var Application = {
  init: function () {
    var user = localStorage.getItem("user");

    var loader = function () {
      return Application.get_item();
    }

    if (user === null) {
      return this.add_user(loader);
    }

    return loader();
  },

  reinit: function () {
    if (window.uiChart)
      window.uiChart.destroy();

    $(".question").removeClass('selected');

    $("#ui-questions").removeClass('result');
    $(".question-cell > h2").html('');
    $(".question-cell > h4").html('');
    $(".question-cell > h3 > b").html('0');

    Application.get_item();
  },

  vk: function (callback) {
    if (window.name.indexOf('fXD') != 0)
      return callback();

    $.getScript("//vk.com/js/api/xd_connection.js?2", function () {
      VK.init(function () {
        var user = (function () {
          return decodeURIComponent((new RegExp('[?|&]viewer_id=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [, ""])[1].replace(/\+/g, '%20')) || null
        })();

        if (user.match(/\d+/))
          window.vkid = user;

        return callback();

      }, callback, '5.28');
    });
  },

  correct_number: function (number, titles, callback) {
    cases = [2, 0, 1, 1, 1, 2];
    return number + " " + titles[(number % 100 > 4 && number % 100 < 20) ? 2 : cases[(number % 10 < 5) ? number % 10 : 5]];
  },

  get_item: function (callback) {
    API.query("/getItems", 'GET', {token: this.get_user}, function (response) {
      UI.questions(response.result.items);

      if (typeof callback === 'function')
        return callback();
    });
  },

  add_view: function (views, callback) {
    var data = {};
    data.views = {};

    $.each(views, function(k, v) {
      if (v === 'left') {
        data.views[k] = 'first';
      }

      if (v === 'right') {
        data.views[k] = 'last';
      }
    });

    data.token = this.get_user;

    API.query("/setViewed", 'POST', data, function (response) {
      if (typeof callback === 'function')
        return callback();
    });
  },

  get_user: function () {
    var user = localStorage.getItem("user");

    if (user === null) {
      return UI.error('local storage is empty', 500);
    }

    return user;
  },

  add_user: function (callback) {
    var uniqid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
      var r = Math.random() * 16 | 0;
      var v = c == 'x' ? r : (r & 0x3 | 0x8);

      return v.toString(16);
    });

    var data = {
      'client': 'web',
      'uniqid': uniqid
    }

    if (typeof window.vkid !== 'undefined') {
      var data = {
        'client': 'vk',
        'uniqid': window.vkid
      }
    }

    API.query("/register", 'POST', data, function (response) {
      if (response.ok) {
        localStorage.setItem("user", response.result.token);
      }

      if (typeof callback === 'function')
        return callback();
    });
  }
}


$(document).ready(function () {
  Application.vk(function () {
    Application.init();
  });
});
