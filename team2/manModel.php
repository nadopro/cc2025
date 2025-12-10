<?php
// manModel.php
// 전제: index.php에서 이미 $conn = connectDB(); , Bootstrap5 로드됨.

$catMain = [
    1 => '상의',
    2 => '하의',
    3 => '신발',
];

$catSub = [
    1 => [11=>'T-Shirt', 12=>'Dress', 13=>'Outer'],
    2 => [21=>'Pants', 22=>'Skirt'],
    3 => [30=>'Shoes']
];

$catSubJson = json_encode($catSub, JSON_UNESCAPED_UNICODE);

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $main = (int)($_POST['cat_main'] ?? 0);
    $sub  = (int)($_POST['cat_sub']  ?? 0);
    $cat  = $main===3 ? 30 : $sub;

    $name  = trim($_POST['name']  ?? '');
    $price = (int)($_POST['price'] ?? 0);
    $size  = trim($_POST['size']  ?? '');
    $color = trim($_POST['color'] ?? '');
    $memo  = trim($_POST['memo']  ?? '');

    if(!$main || !$name || $price<=0 || ($main!==3 && !$sub)) {
        echo "<script>alert('필수 정보 확인!');history.back();</script>";
    } else {
        foreach(['data/1/','data/2/','data/3/'] as $d) if(!is_dir($d)) mkdir($d,0777,true);

        $imgs=['img1'=>'','img2'=>'','img3'=>''];
        for($i=1;$i<=3;$i++){
            $f='img'.$i;
            if(isset($_FILES[$f]) && $_FILES[$f]['error']===UPLOAD_ERR_OK){
                $ext = strtolower(pathinfo($_FILES[$f]['name'],PATHINFO_EXTENSION)) ?: 'jpg';
                $nm  = date("YmdHis")."_$i.$ext";
                $pth = ['data/1/','data/2/','data/3/'][$i-1] . $nm;
                move_uploaded_file($_FILES[$f]['tmp_name'],$pth);
                $imgs[$f]=$pth;
            }
        }

        $q="INSERT INTO model(cat,name,price,size,color,img1,img2,img3,memo)VALUES(?,?,?,?,?,?,?,?,?)";
        $st=$conn->prepare($q);
        $st->bind_param("isissssss",$cat,$name,$price,$size,$color,$imgs['img1'],$imgs['img2'],$imgs['img3'],$memo);
        if($st->execute()){
            echo "<script>alert('등록됨');location.href='index.php?cmd=manModel';</script>";
        }
    }
    exit;
}
?>

<style>
  body {
      background:#f8fafc;
  }
  .narrow-card {
      border:none;
      border-radius:18px;
      box-shadow:0 6px 18px rgba(0,0,0,0.06);
  }
  .form-label {
      font-size:0.82rem;
      font-weight:700;
      letter-spacing:0.05em;
      color:#000;
      text-transform:uppercase;
  }
  .form-control, .form-select, textarea {
      border-radius:12px;
      font-size:0.9rem;
  }
  .btn {
      border-radius:999px;
  }
</style>

<div class="container d-flex justify-content-center mt-5">
  <div class="card narrow-card narrow-card w-100" style="max-width:520px;">
    <div class="card-body p-4">
      <h5 class="fw-bold mb-4 text-center text-dark">Product Upload</h5>

      <form method="post" enctype="multipart/form-data">

        <!-- 대분류 -->
        <div class="mb-4">
          <label class="form-label">Category</label>
          <select name="cat_main" id="cat_main" class="form-select" required>
            <option value="">Select</option>
            <?php foreach($catMain as $k=>$v): ?>
              <option value="<?= $k ?>"><?= htmlspecialchars($v) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- 소분류 (상의/하의만) -->
        <div class="mb-4">
          <label class="form-label">Sub Category</label>
          <select name="cat_sub" id="cat_sub" class="form-select" disabled>
            <option value="">Select sub</option>
          </select>
          <div class="form-text small text-muted">Shoes auto-saved as 30</div>
        </div>

        <div class="mb-4">
          <label class="form-label">Product name</label>
          <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-4">
          <label class="form-label">Price</label>
          <input type="number" name="price" class="form-control" required>
        </div>

        <div class="mb-4">
          <label class="form-label">Sizes</label>
          <input type="text" name="size" class="form-control" placeholder="S,M,L">
        </div>

        <div class="mb-4">
          <label class="form-label">Colors</label>
          <input type="text" name="color" class="form-control" placeholder="black, white">
        </div>

        <div class="mb-4">
          <label class="form-label">Images</label>
          <input type="file" name="img1" class="form-control mb-2">
          <input type="file" name="img2" class="form-control mb-2">
          <input type="file" name="img3" class="form-control">
        </div>

        <div class="mb-4">
          <label class="form-label">Memo</label>
          <textarea name="memo" rows="3" class="form-control"></textarea>
        </div>

        <div class="text-center">
          <button type="submit" class="btn btn-dark px-5">Upload</button>
        </div>

      </form>
    </div>
  </div>
</div>

<script>
const map = <?= $catSubJson ?>;
const main = document.getElementById("cat_main");
const sub  = document.getElementById("cat_sub");

main.addEventListener("change",()=>{
  const v = parseInt(main.value);
  sub.innerHTML="";
  if(v===3){
      sub.disabled=true;
      const o=document.createElement("option");
      o.value=""; o.textContent="No sub for shoes";
      sub.appendChild(o);
  }else{
      const m=map[v];
      if(!m || Object.keys(m).length<=1){
          sub.disabled=true;
          const o=document.createElement("option");
          o.value=""; o.textContent="No sub";
          sub.appendChild(o);
      }else{
          sub.disabled=false;
          const first=document.createElement("option");
          first.value=""; first.textContent="Select";
          sub.appendChild(first);
          Object.entries(m).forEach(([c,nm])=>{
              if(parseInt(c)>=30) return;
              const o=document.createElement("option");
              o.value=c; o.textContent=nm;
              sub.appendChild(o);
          });
      }
  }
});
</script>
