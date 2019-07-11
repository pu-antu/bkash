<html>
<title>Package Tutorial In laravel</title>
<body>
<h1>Hello World</h1>
<form action="{{url('/bkash')}}">
    @csrf
    <inupt type="text" name="name"></inupt>
    <inupt type="email" name="name"></inupt>
    <textare name="msg"></textare>
    <button type="submit"> Submit</button>
</form>
</body>
</html>