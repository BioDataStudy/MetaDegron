<script>
if(! window.localStorage){
	alert("localStorage!");
}else{
	var storage=window.localStorage;
	storage["a"]=1;
	storage.b=1;
	storage.setItem("c",3);
	var a=storage.a;
	console.log(a);
}
</script>