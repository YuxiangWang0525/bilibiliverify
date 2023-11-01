	function requestcode(){
				var uid = document.getElementById('InputUID').value;
				var qq = document.getElementById('QQNInput').value;
				var xhr = new XMLHttpRequest();
				xhr.open('POST', 'Your_Server_Address/index.php');
				xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
				var params = 'qq=' + encodeURIComponent(qq) + '&uid=' + encodeURIComponent(uid);
				xhr.onreadystatechange = function() {
				  if (xhr.readyState === XMLHttpRequest.DONE) {
				    if (xhr.status === 200) {
				      var response = JSON.parse(xhr.responseText);
				      
				      if (response.code === 100) {
				        alert('请输入bilibili uid和QQ号!');
				      } else if (response.code === 0) {
				        var key = response.key;
						document.getElementById('keyui').removeAttribute('hidden');
						document.getElementById('key').value = key;
				      }
				    } else {
				      console.error('请求失败，状态码：' + xhr.status);
				    }
				  }
				};
				xhr.send(params);
			}