<style>
    .category-title {
        font-family: 'Gowun Dodum', sans-serif;
        font-size: 32px;
        text-align: center;
        margin-top: 40px;
        margin-bottom: 50px;
    }
    .category-box {
        text-align: center;
        cursor: pointer;
        transition: 0.2s;
        padding: 20px;
        border-radius: 10px;
    }
    .category-box:hover {
        transform: translateY(-5px);
        background: #f6f9ff;
    }
    .category-img {
        width: 130px;
        height: 130px;
        object-fit: contain;
        margin-bottom: 15px;
    }
    .category-label {
        font-size: 20px;
        font-weight: 500;
    }
    .bottom-title {
        font-family: 'Gowun Dodum', sans-serif;
        font-size: 28px;
        text-align: center;
        margin-top: 50px;
    }
</style>

<div class="container">

    <div class="category-title">
        2000년의 지혜, 공자의 가르침으로 마음의 방향을 잡아보세요.
    </div>

    <!-- 첫 번째 줄 : 3개 -->
    <div class="row justify-content-center g-4 mb-4">

        <div class="col-6 col-md-4 col-lg-3">
            <a href="index.php?cmd=list&cat=1" class="text-decoration-none text-dark">
                <div class="category-box">
                    <img src="img/cat_1.png" class="category-img">
                    <div class="category-label"><?= $catList[1] ?></div>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-4 col-lg-3">
            <a href="index.php?cmd=list&cat=2" class="text-decoration-none text-dark">
                <div class="category-box">
                    <img src="img/cat_2.png" class="category-img">
                    <div class="category-label"><?= $catList[2] ?></div>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-4 col-lg-3">
            <a href="index.php?cmd=list&cat=3" class="text-decoration-none text-dark">
                <div class="category-box">
                    <img src="img/cat_3.png" class="category-img">
                    <div class="category-label"><?= $catList[3] ?></div>
                </div>
            </a>
        </div>

    </div>

    <!-- 두 번째 줄 : 2개 (가운데 정렬) -->
    <div class="row justify-content-center g-4 mb-4">

        <div class="col-6 col-md-4 col-lg-3">
            <a href="index.php?cmd=list&cat=4" class="text-decoration-none text-dark">
                <div class="category-box">
                    <img src="img/cat_4.png" class="category-img">
                    <div class="category-label"><?= $catList[4] ?></div>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-4 col-lg-3">
            <a href="index.php?cmd=list&cat=5" class="text-decoration-none text-dark">
                <div class="category-box">
                    <img src="img/cat_5.png" class="category-img">
                    <div class="category-label"><?= $catList[5] ?></div>
                </div>
            </a>
        </div>

    </div>

    <div class="bottom-title">
        오늘 당신의 고민을 선택하세요.
    </div>

</div>
