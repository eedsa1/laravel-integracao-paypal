<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<form action="{{ route('testDiscordWebhook') }}" method="POST" name="form2" id="form2">
		{{ csrf_field() }}
		<button>executar</button>
		
	</form>
</body>
</html>