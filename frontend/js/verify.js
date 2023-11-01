	function requestcode(){
				var uid = document.getElementById('InputUID').value;
				var xhr = new XMLHttpRequest();
				xhr.open('POST', 'Your_Server_Address/verify.php');
				xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
				var params = '&uid=' + encodeURIComponent(uid);
				xhr.onreadystatechange = function() {
				  if (xhr.readyState === XMLHttpRequest.DONE) {
				    if (xhr.status === 200) {
				      var response = JSON.parse(xhr.responseText);
				      
				      if (response.code === 100) {
				        alert('请输入bilibili uid!');
				      } else if (response.code === 0) {
				        alert("验证成功!请使用您之前填入的QQ号加群 自动程序将予以通过!");
				      } else if (response.code === 500) {
				        alert("一个B站账号只能用于通过一个入群申请,您已有一个入群的账号,请勿重复验证!");
				      } else if (response.code === 80) {
				        alert("不存在此UID!请先获取验证码");
				      } else if (response.code === 450) {
				        alert("您尚未将验证码发送给“[您的组织名称]”官方账号!");
				      } else if (response.code === 490) {
				        alert("您尚未关注“[您的组织名称]”!");
				      }
				    } else {
				      console.error('请求失败，状态码：' + xhr.status);
				    }
				  }
				};
				xhr.send(params);
			}
