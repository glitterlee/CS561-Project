/*THE ACTIONS INTERACTED WITH BACKEND*/



/**
 * calculate number of joined students
 * @param {object} data
 * @return {number}
 */
function studentJoinNumber(data) {
  if (data['students'] === undefined) {
    return 0;
  }
  else if (data['students'] !== null) {
    return data['students'].length;
  }
  else {
    return 0;
  }
}

/**
 * generate a column in question table from data
 * @param {object} data
 * @return {string}
 */
function columnInQuestionTable(data) {
  if (data['id'] === undefined || data['title'] === undefined || data['stdnt_first_name'] === undefined
    || data['stdnt_last_name'] === undefined || data['create_time'] === undefined
    || data['status'] === undefined) {

    return "";
  }

  var string = '<tr id="question_' + data['id'] + '">';
  string += '<td><a href="ta_question.php?question_id=' + data['id'] + '">' + data['title'] + '</a></td>';
  string += '<td>' + data['stdnt_first_name'] + ' ' + data['stdnt_last_name'] + '</td>';
  string += '<td>' + data['create_time'] + '</td>';
  //  if question is signed, show ta's name
  if (data['status'] === 'signed') {
    string += '<td>' + data['ta_first_name'] + ' ' + data['ta_last_name'] + '</td>';
  }
  else {
    string += '<td>' + data['status'] + '</td>';
  }
  string += '<td><span class="memberConut">' + studentJoinNumber(data) + '</span></td>';
  //  if question is proposed, show add buttion
  if (data['status'] === 'proposed') {
    string += '<td><button id="assignBtn" class="tableAssign">Assign</button></td>';
    string += '<td><button id="answerBtn" class="tableAnswer">Answer</button></td>';
  }
  else {
    string += '<td></td><td></td>';
  }
  string += '<td><span id="deleteBtn" class="tableDelete"></span></td>';
  string += '</tr>';
  return string;
}

/**
 * update user name
 * @param {string} name
 */
function updateUserName(name) {
  $("#logout_name").html(name);
}

/**
 * call api - get: question list
 * @param {string} class_id
 * @param {function} callback
 */
function getQuestionList(class_id, callback) {
  $.ajax({
    type: "get",
    url: "actions/question_list.php?class_id=" + class_id,
    async: true,
    dataType: "json",
    success: function (data) {
      callback(data);
    },
    error: function (XMLHttpRequest, textStatus, errorThrown) {
      alert(XMLHttpRequest.status);
      alert(XMLHttpRequest.readyState);
      alert(textStatus);
    }
  });
};

/**
 * call api - post: assign question
 * @param {string} question_id
 * @param {function} callback
 */
function assignQuestion(question_id, callback) {
  $.ajax({
    type: "post",
    url: "actions/assign_question.php",
    async: true,
    data: { "question_id": question_id },
    dataType: "json",
    success: function (data) {
      showInfo(data.SUCCESS);
      callback(data);
    },
    error: function (XMLHttpRequest, textStatus, errorThrown) {
      alert(XMLHttpRequest.status);
      alert(XMLHttpRequest.readyState);
      alert(textStatus);
    }
  });
};

/**
 * call api - post: assign question
 * @param {string} question_id
 * @param {function} callback
 */
function deleteQuestion(question_id, callback) {
  $.ajax({
    type: "delete",
    url: "actions/questions.php/" + question_id,
    async: true,
    dataType: "json",
    success: function (data) {
      callback(data);
    },
    error: function (XMLHttpRequest, textStatus, errorThrown) {
      alert(XMLHttpRequest.status);
      alert(XMLHttpRequest.readyState);
      alert(textStatus);
    }
  });
};

function getQuestionDetail(questionId) {
  var classid = getGetParameter('class_id');
  $.ajax({
    type: "get",
    url: "actions/getQuestionDetail.php?classid=" + classid + "&questionid=" + questionId,
    async: false,
    dataType: 'json',
    success: function (data) {
      if (data.ERROR == 0) {
        showAnswerDialog(data.DATA.QUESTION, questionId);
      } else showError(data.MESSAGE);
    },
    error: function (XMLHttpRequest, textStatus, errorThrown) {
      alert(XMLHttpRequest.status);
      alert(XMLHttpRequest.readyState);
      alert(textStatus);
    }
  });
};

function showAnswerDialog(data, questionId) {
  if (document.getElementById("questionDiv")) {
    document.getElementById("questionDiv").style.height = window.innerHeight + "px";
  }
  $.formBox.openDialog('questionForm');
  $('#question_id').val(questionId);
  $('.questionForm h6').html(data[2]);
  $('.questionForm p').html(data[1]);
}

function answerQuestion() {
  $.ajax({
    type: "post",
    url: "actions/answer_question.php",
    async: false,
    data: $('#dialog .questionForm form').serializeForm(),
    dataType: 'json',
    success: function (data) {
      initilizeQuestion();
      showInfo(data.MESSAGE);
    },
    error: function (XMLHttpRequest, textStatus, errorThrown) {
      alert(XMLHttpRequest.status);
      alert(XMLHttpRequest.readyState);
      alert(textStatus);
    }
  });
}

function initilizeQuestion() {
  //  require parameters in url
  var class_id = getUrlParam('class_id');

  //  get questions in this class, implement elements operation in callback
  getQuestionList(class_id, (data) => {
    $('#main .data table tbody').html('');
    var questions = data["questions"];
    //Show questions
    for (i in questions) {
      $('#main .data table tbody').append(columnInQuestionTable(questions[i]));
      var question_str = JSON.stringify(questions[i]);
      setSession("question_" + questions[i]['id'], question_str);
    }
  });
}

/*INIT*/
$('document').ready(function () {
  initilizeQuestion();

  //  update user name
  updateUserName(getSession('user_name'));

  //  bind click event for assign class button
  $('#main .data table').on("click", ".tableAssign", function () {
    var question_id = $(this).parent().parent().attr('id').substring("question_".length);
    console.log("question_id = " + question_id);
    assignQuestion(question_id, (data) => {
      window.location.reload();
    });
  });

  //  bind click event for answer class button
  $('#main .data table').on("click", ".tableAnswer", function () {
    var question_id = $(this).parent().parent().attr('id').substring("question_".length);
    getQuestionDetail(question_id);
  });

  //  bind click event for delete class button
  $('#main .data table').on("click", ".tableDelete", function () {
    var question_id = $(this).parent().parent().attr('id').substring("question_".length);
    var question_title = $(this).parent().parent().children().first().text();
    console.log("question_id = " + question_id);
    var msg = "Are you sure to delete question '" + question_title + "'?";
    if (confirm(msg) == true) {
      deleteQuestion(question_id, (data) => {
        window.location.reload();
      });
      return true;
    }
    else {
      return false;
    }
  });

  $('#dialog .questionForm .submitBtn').click(() => {
    answerQuestion();
    $('#dialog .questionForm .close').trigger('click');
  });
});


$(function () {
  // Set up tour
  $('body').pagewalkthrough({
    name: 'introduction',
    steps: [{
      popup: {
        content: '<h3>Welcome!</h3>Start a tutorial?',
        type: 'modal'
      }
    }, {
      wrapper: '#titleTag h4',
      popup: {
        content: 'You are in the TA Class Detail Page now.',
        type: 'tooltip',
        position: 'bottom'
      }
    }, {
      wrapper: '#questionList tbody tr',
      popup: {
        content: 'Check question information.',
        type: 'tooltip',
        position: 'top'
      }
    }, {
      wrapper: '#questionList tbody tr td',
      popup: {
        content: 'Click to check the detail.',
        type: 'tooltip',
        position: 'right'
      }
    }, {
      wrapper: '#questionList tbody tr td span',
      popup: {
        content: 'Number of people who liked this question.',
        type: 'tooltip',
        position: 'top'
      }
    }, {
      wrapper: '#assignBtn',
      popup: {
        content: 'Assign this question to you.',
        type: 'tooltip',
        position: 'top'
      }
    }, {
      wrapper: '#answerBtn',
      popup: {
        content: 'Answer this question.',
        type: 'tooltip',
        position: 'left'
      }
    }, {
      wrapper: '#deleteBtn',
      popup: {
        content: 'Delete this question.',
        type: 'tooltip',
        position: 'left'
      }
    }, {
      wrapper: '#toStudent',
      popup: {
        content: 'Clike here go to Student Dashboard.',
        type: 'tooltip',
        position: 'left'
      }
    }]
  });

  if (getSession('tutorial') == 1) {
    $('body').pagewalkthrough('show');
  }

});
