짧은 주소로 리다이렉트 애드온
-----------------------------

사용자나 검색로봇이 아래와 같은 주소를 방문할 경우

    http://www.example.com/xe/index.php?mid=board&document_srl=1234
	http://www.example.com/xe/index.php?mid=board

각각 아래와 같은 주소로 301 리다이렉트시켜 줍니다.

    http://www.example.com/xe/board/1234
	http://www.example.com/xe/board

URL에 페이지, 검색어 등의 파라미터가 포함된 경우에는 리다이렉트하지 않습니다.

짧은 주소를 사용하지 않도록 설정된 사이트에서는 동작하지 않습니다.
