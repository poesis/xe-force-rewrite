짧은 주소로 리다이렉트 애드온
-----------------------------

사용자나 검색로봇이 아래와 같은 주소를 방문할 경우

    http://www.example.com/xe/index.php?mid=board&document_srl=1234
	http://www.example.com/xe/index.php?mid=board

각각 아래와 같은 주소로 301 리다이렉트시켜 줍니다.

    http://www.example.com/xe/board/1234
	http://www.example.com/xe/board

URL에 페이지, 검색어 등의 파라미터가 포함된 경우에도 리다이렉트하도록 설정할 수 있습니다.
이 기능을 사용할 경우 세션을 사용하여 페이지, 검색어 등의 파라미터를 별도 저장합니다.
상황에 따라서는 별도 저장된 파라미터가 정상적으로 적용되지 않을 수 있으므로
반드시 필요한 경우에만 이 기능을 사용하시기 바랍니다.

짧은 주소를 사용하지 않도록 설정된 사이트에서는 동작하지 않습니다.
