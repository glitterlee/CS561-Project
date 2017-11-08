/*THE ACTIONS INTERACTED WITH BACKEND*/
//get get parameter
function getGetParameter(name) { 
	var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i"); 
	var r = window.location.search.substr(1).match(reg); 
	if (r != null) return unescape(r[2]); 
	return null; 
} 

function showQuestionDetail(data){		//::TODO
	$('.questionDetail h5').html(data[2]);
	$('.questionDetail p').html(data[1]);
	$.formBox.openDialog('questionDetail');
}

function showUserLoginInfo(data){
	if(data==null) return;
	var str='';
	if(data.ROLE=='1') str='&nbsp;<a href="./ta.html">Switch to TA Dashboard</a>'
	$('.user ').html('<span>'+data.FIRSTNAME+' '+data.LASTNAME+'</span>&nbsp;<span style="cursor:pointer;" onclick="logout();">Logout</span>'+str);
}

function getLoginInfo(){
	$.ajax({
		type: "post",
		url:"actions/checkUserType.php",
		async:false,
		dataType:"json",
		success: function(data) {
			if(data.ERROR==0) showUserLoginInfo(data.DATA.USERINFO);
			else openToast(data.MESSAGE);
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			alert(XMLHttpRequest.status);
			alert(XMLHttpRequest.readyState);
			alert(textStatus);
		}
	});
}

//insert a column in question table from data
function insertColumnInQuestionTable(data){
	var tbodyClassName = '#main .data table tbody'
	$(tbodyClassName).append('<tr questionId="'+data.ID+'"></tr>');
	$obj = $(tbodyClassName + ' tr:last-child');
	$obj.append('<td onclick="getQuestionDetail('+data.ID+');">'+data.TITLE+'</td>')
		.append('<td>'+data.NAME+'</td>').append('<td>'+data.CREATE_TIME+'</td>')
		.append('<td>'+data.STATUS+'</td>')
		.append('<td><span class="memberConut">'+data.NUM_JOIN+'</span></td>');
	if(data.ISMINE=='0'&&data.ISJOIN=='0')
		$obj.append('<td><span class="tableAddition" onclick="joinInAQuestion('+data.ID+');"></span></td>');
	else if(data.ISMINE=='1')
		$obj.append('<td><span></span></td>');
	else
		$obj.append('<td><span class="tableCancel" onclick="quitFromAQuestion('+data.ID+');"></span></td>');
}

function refreshAndMoveToAQuestion(id){
	var screenOffset = $('#main .data table tbody tr[questionId="'+id+'"]').offset().top - $(document).scrollTop();
	getQuestionList(); 
	$('html, body').animate({  
        	scrollTop: $('#main .data table tbody tr[questionId="'+id+'"]').offset().top - screenOffset 
       	}, 700); 
};

//action:getStudentsClasses
function getStudentsClasses(){
	$.ajax({
		type: "get",
		url:"actions/query_class.php?category=student",
		async:false,
		dataType:"json",
		success: function(data) {
			if(!data.ERROR){
				$.each(data.class_info,function(i,item){
					$str='';
					if (item.id==getGetParameter('classId')){
						$str='style="color:black";';
					}
					$("#classNav").append('<a href="./studentQuestions.html?classId='+item.id+'" '+$str+'>'+item.name+'</a>&nbsp;');
				});
			}else openToast(data.ERROR);
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			alert(XMLHttpRequest.status);
			alert(XMLHttpRequest.readyState);
			alert(textStatus);
		}
	});
};

//action:getQuestionList
function getQuestionList(){
	var classid = getGetParameter('classId');
	$.ajax({
		type: "get",
		url:"actions/getQuestionList.php?classid="+classid,
		async: false,
		dataType:"json",
		success: function(data) {
			if(data.ERROR == 0){
				$('#main .data table tbody').html('');
				data = data.DATA;
				$.each(data.QUESTIONS, function(i,item) {
					insertColumnInQuestionTable(item);
				});
			}else openToast(data.MESSAGE);
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			alert(XMLHttpRequest.status);
			alert(XMLHttpRequest.readyState);
			alert(textStatus);
		}
	});
};

//action:getQuestionDetail
function getQuestionDetail(questionId){
	var classid = getGetParameter('classId');
	$.ajax({
		type: "get",
		url:"actions/getQuestionDetail.php?classid="+classid+"&questionid="+questionId,
		async: false,
		dataType:'json',
		success: function(data) {
			if(data.ERROR == 0){
				showQuestionDetail(data.DATA.QUESTION);
			}else openToast(data.MESSAGE);		
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			alert(XMLHttpRequest.status);
			alert(XMLHttpRequest.readyState);
			alert(textStatus);
		}
	});
};

//action:createNewQuestion
function createNewQuestion(){
	classid = getGetParameter('classId');
	$.ajax({
		type: "post",
		url:"actions/addNewQuestion.php?classid="+classid,
		async: false,
		data:$('#dialog .questionForm form').serializeForm(),
		dataType:'json',
		success: function(data) {
			openToast(data.MESSAGE);
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			alert(XMLHttpRequest.status);
			alert(XMLHttpRequest.readyState);
			alert(textStatus);
		}
	});
};

//action:JoinInQuestion
function joinInAQuestion(id){
	classid = getGetParameter('classId');
	$str = '{"id":'+id+'}';
	$.ajax({
		type: "post",
		url:"actions/joinInQuestion.php?classid="+classid,
		async: false,
		data: $str,
		dataType:'json',
		success: function(data) {
			if(data.MESSAGE!=null) openToast(data.MESSAGE);
			if(data.ERROR == 0)	refreshAndMoveToAQuestion(id);
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			alert(XMLHttpRequest.status);
			alert(XMLHttpRequest.readyState);
			alert(textStatus);
		}
	});
};

//action:QuitFromQuestion
function quitFromAQuestion(id){
	classid = getGetParameter('classId');
	$str = '{"id":'+id+'}';
	$.ajax({
		type: "post",
		url:"actions/quitFromQuestion.php?classid="+classid,
		async: false,
		data: $str,
		dataType:'json',
		success: function(data) {
			if(data.MESSAGE!=null) openToast(data.MESSAGE);
			if(data.ERROR == 0)	refreshAndMoveToAQuestion(id);
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			alert(XMLHttpRequest.status);
			alert(XMLHttpRequest.readyState);
			alert(textStatus);
		}
	});
};

/*INIT*/
$('document').ready(function(){

	//bind click event for add question button
	$('.openQFormDialog').click(function(){
		$.formBox.openDialog('questionForm');
		//time picker plugin
		$('.timeDetailInput').timepicker({ 'scrollDefault': 'now' }).timepicker('setTime', new Date());
	});
	
	getStudentsClasses();
	//Show questions
	getQuestionList();

	//Init the Timepicker
	getAvailableTime();

	//Add question (bind click event for post question button)
	$('#dialog .questionForm .submitBtn').click(function(){
		if($('#dialog .questionForm form').checkForm()==true){
			createNewQuestion();
			$('#dialog .questionForm .close').trigger('click');
			getQuestionList();
		}
	});

});