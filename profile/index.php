<?php include '../global/connect.php'; ?>
<?php if (!getConfig('global_userProfile', 'bool', $conn)) { back(); } ?>

<!DOCTYPE html>
<html lang="th">

<head>
    <?php include '../global/head.php'; ?>
    <?php
        if (isset($_GET['search'])) $id = $_GET['search'];
        else $id = $_SESSION['id'];

        if (!isValidUserID($id, $conn)) back();

        $query = "SELECT * FROM `user` WHERE id = '$id'";
        $result = mysqli_query($conn, $query);
     
        if (!$result) {
            die('Could not get data: ' . mysqli_error($conn));
        }
        
        if (!$_SESSION['dark_mode'])
        $profile_background = "../assets/images/background/bg_light_pastel.jpg";
        else
        $profile_background = "../assets/images/background/bg_dark_resize.jpg";

        $query_profile = "SELECT * FROM `profile` WHERE id = '$id'";
        $result_profile = mysqli_query($conn, $query_profile);


     while ($row = mysqli_fetch_array($result_profile, MYSQLI_ASSOC)) {
     if ($row['background'] != null) $profile_background = $row['background'];
     }
    
    ?>
    <style>
        body {
            background: url(<?php echo $profile_background ?>) no-repeat center center fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            background-size: cover;
            -o-background-size: cover;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-normal fixed-top scrolling-navbar" id="nav"
        role="navigation">
        <?php include '../global/navbar.php'; ?>
    </nav>
    <?php if (isset($_GET['search']) || (isset($_SESSION['id']))) {
        $id;
        if (isset($_GET['search']))
            $id = $_GET['search'];
        else
            $id = $_SESSION['id'];
        
        $profile_achi = "";
                    
            $profile_prefix = getUserdata($id, 'prefix', $conn);
            if ($profile_prefix == 'นาย') $profile_prefix_en = 'Mr. ';
            else if ($profile_prefix == 'นาง') $profile_prefix_en = 'Mrs. ';
            else if ($profile_prefix == 'เด็กชาย') $profile_prefix_en = 'Master ';
            else if ($profile_prefix == 'เด็กหญิง' || $profile_prefix == 'นางสาว') $profile_prefix_en = 'Miss ';
            else $profile_prefix_en = "";

            $profile_name = $profile_prefix . getUserdata($id, 'firstname', $conn) . ' ' . getUserdata($id, 'lastname', $conn);
            $profile_name_en = $profile_prefix_en . getUserdata($id, 'firstname_en', $conn) . ' ' . getUserdata($id, 'lastname_en', $conn);
            $profile_id = $id;
            $profile_grade = getUserdata($id, 'grade', $conn);

            $profile_room = getUserdata($id, 'class', $conn);
            if ($profile_room == 1) $profile_class = "EMSP";
            else if ($profile_room == 5) $profile_class = "วมว.";
            else $profile_class = "ปกติ";
            if ($profile_grade > 6) $profile_class_detail = "<strong>ศิษย์เก่า</strong><br>";
            else if ($profile_grade >= 1 && $profile_grade <= 6) $profile_class_detail = "<strong>ระดับชั้น</strong> " . $profile_grade . "/" . $profile_room . " (" . $profile_class . ")<br>";
            else $profile_class_detail = "";

            $profile_email = getUserdata($id, 'email', $conn);
            if ($profile_email != null) {
                $profile_email = '<strong>อีเมล</strong> <a href="mailto:' . $profile_email . '">'. $profile_email .'</a>';
            }
        
            $profile_image = getProfilePicture($id, $conn);

            $profile_greets = getProfileData($id, 'greetings', $conn);


        
            if (getAchievementdata($id, 'betaTester', $conn))
                $profile_achi .= '<div class="col-4 col-sm-4 mb-3"><a class="material-tooltip-main" data-toggle="tooltip" title="Beta Tester (LEGENDARY)"><img src="../assets/images/achievement/beta-tester-icon_resize.gif" alt="Beta Tester (LEGENDARY)" class="img-fluid w-100 justify-content-center"></a></div>';
            if (getAchievementdata($id, 'WebDevTycoon', $conn))
                $profile_achi .= '<div class="col-4 col-sm-4 mb-3"><a class="material-tooltip-main" data-toggle="tooltip" title="Web Dev Tycoon (UNOBTAINABLE)"><img src="../assets/images/achievement/Web_dev_tycoon_icon_resize.gif" alt="Web Dev Tycoon (UNOBTAINABLE)" class="img-fluid w-100 justify-content-center"></a></div>';
            if (getAchievementdata($id, 'the4thFloor', $conn))
                $profile_achi .= '<div class="col-4 col-sm-4 mb-3"><a class="material-tooltip-main" data-toggle="tooltip" title="The 4th Floor (RARE)"><img src="../assets/images/achievement/stair.png" alt="The 4th Floor (RARE)" class="img-fluid w-100 justify-content-center"></a></div>';
            if (getAchievementdata($id, 'Xmas', $conn))
                $profile_achi .= '<div class="col-4 col-sm-4 mb-3"><a class="material-tooltip-main" data-toggle="tooltip" title="Merry Christmas~ (UNCOMMON)"><img src="../assets/images/achievement/xmas_resize.png" alt="Merry Christmas~ (UNCOMMON)" class="img-fluid w-100 justify-content-center"></a></div>';
        
    ?>
    <div class="container" id="container" style="padding-top: 88px">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-12">
                        <h1> <?php echo $profile_name; ?></h1>
                        <h5> <?php echo $profile_name_en; ?></h5>
                    </div>
                    <?php if(!isset($_GET['search'])) { ?>
                        <div class="fixed-action-btn" style="bottom: 45px; right: 24px;">
                            <a class="btn-floating btn-lg btn-warning" href="edit.php"><i class="fas fa-pencil-alt"></i></a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="mb-3"></div>
        <div class="row">
            <div class="col-md-4 col-sm-12">
                <div class="media">
                    <img src="<?php echo $profile_image; ?>" class="img-fluid w-100" alt="Profile">
                </div>
                <div class="row">
                    <div class="col-md-12 text-left">
                        <div class="mb-3"></div>
                        <div class="card">
                            <div class="card-body">
                                <strong>รหัสนักเรียน</strong> <?php echo $profile_id ?><br>
                                <?php echo $profile_class_detail; ?>
                                <?php echo $profile_email; ?>
                            </div>
                        </div>
                        <div class="mb-3"></div>
                        <div class="card">
                            <div class="card-body">
                                <h2>Achievement</h2>
                                <div class="mb-3"></div>
                                <div class="row">
                                    <?php echo $profile_achi; ?>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
            <?php if ($profile_greets != null) { ?>
                <div class="card">
                    <div class="card-body">
                        <p><?php echo $profile_greets ?></p>
                    </div>
                </div>
                <div class="mb-3"></div>
                <?php } ?>
                <?php if ($id == 604019) { ?>
                <div class="card">
                    <div class="card-body">
                        <h2>ประวัติการศึกษา</h2>
                        <div class="mb-3"></div>
                        <div class="row">
                            <div class="col-9">
                                <h4>โรงเรียนสาธิตมหาวิทยาลัยขอนแก่น (มอดินแดง)</h4>
                            </div>
                            <div class="col-3">
                                <h5 class="text-right">2008 - 2014</h5>
                            </div>
                        </div>
                        <h5><span class="badge badge-secondary">ระดับประถมศึกษา</span></h5>

                        <div class="row">
                            <div class="col-9">
                                <h4>โรงเรียนสาธิตมหาวิทยาลัยขอนแก่น (มอดินแดง)</h4>
                            </div>
                            <div class="col-3">
                                <h5 class="text-right">2014 - 2017</h5>
                            </div>
                        </div>
                        <h5><span class="badge badge-secondary">ระดับมัธยมศึกษาตอนต้น</span></h5>
                        <div class="row">
                            <div class="col-9">
                                <h4>โรงเรียนสาธิตมหาวิทยาลัยขอนแก่น (มอดินแดง)</h4>
                            </div>
                            <div class="col-3">
                                <h5 class="text-right">2017 - ปัจจุบัน</h5>
                            </div>
                        </div>
                        <h5><span class="badge badge-secondary">ระดับมัธยมศึกษาตอนปลาย</span></h5>
                    </div>
                </div>
                <div class="mb-3"></div>
                <div class="card">
                    <div class="card-body">
                        <h2>ประสบการณ์</h2>
                        <div class="mb-3"></div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="row">
                                    <div class="col-9">
                                        <h4>รางวัลรองชนะเลิศอันดับ 1 การแข่งขัน Web Programming Competition
                                            ระดับมัธยมศึกษาตอนปลาย</h4>
                                    </div>
                                    <div class="col-3">
                                        <h5 class="text-right"><span aria-hidden="true"></span>19/08/2562</h5>
                                    </div>
                                </div>
                                <h5><span class="badge badge-secondary">การแข่งขันในงานสัปดาห์วิทยาศาสตร์</span></h5>
                                <p>ขอแสดงความยินดีกับนายพลภณ สุนทรภาส ชั้นมัธยมศึกษาปีที่ 6 อาจารย์ที่ปรึกษา
                                    อาจารย์จุฬารัตน์ สียา ได้รับรางวัลที่ 2 จากการแข่งขัน Web Programming Competition
                                    ระดับมัธยมศึกษาตอนปลาย ในกิจกรรมสัปดาห์วันวิทยาศาสตร์แห่งชาติ ประจำปี
                                    พ.ศ. 2562 วันที่ 18-20 สิงหาคม 2562 ณ คณะวิทยาศาสตร์ มหาวิทยาลัยขอนแก่น
                                </p>
                                <img src="https://webcontest.cs.kku.ac.th/2562/photo/award.jpg" width=100%>
                                <br><a href="https://webcontest.cs.kku.ac.th/index.php?page=result&y=2562"
                                    target="_blank"><i class="fas fa-external-link-alt"></i></a>
                                <a href="https://www.facebook.com/SMD.KKU/posts/2215982308531509" target="_blank"><i
                                        class="fas fa-external-link-alt"></i></a>
                                <div class="mb-3"></div>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <div class="row">
                                    <div class="col-9">
                                        <h4>รางวัลชนะเลิศการแข่งขันสร้างเว็บเพจ (Web editor) ระดับมัธยมศึกษาตอนปลาย</h4>
                                    </div>
                                    <div class="col-3">
                                        <h5 class="text-right"><span aria-hidden="true"></span>14/02/2562</h5>
                                    </div>
                                </div>
                                <h5><span class="badge badge-secondary">Computer Education Open House 2018</span></h5>
                                <p>โรงเรียนสาธิตมหาวิทยาลัยขอนแก่น ฝ่ายมัธยมศึกษา (มอดินแดง)
                                    ขอแสดงความยินดีกับนักเรียนที่ได้รับรางวัลจากการเข้าร่วมกิจกรรม Computer Education
                                    Open House 2018 สาขาคอมพิวเตอร์ศึกษา คณะศึกษาศาสตร์ มหาวิทยาลัยขอนแก่น เมื่อวันที่
                                    14 กุมภาพันธ์ 2562 ที่ผ่านมา ซึ่งนักเรียนที่ได้รับรางวัลมีดังต่อไปนี้ นายวิชยุตม์
                                    สุรินทร์ชมพู และนายธนภัทร เครือน้ำคำ ได้รับรางวัลชนะเลิศการแข่งขันสร้าง Infographics
                                    ระดับมัธยมศึกษาตอนต้น นายรณกร ศรีพอ นายพลภณ สุนทรภาส
                                    และนายสหัสวัต สุดาพรรัตน์ ได้รับรางวัลชนะเลิศการแข่งขันสร้างเว็บเพจ (Web editor)
                                    ระดับมัธยมศึกษาตอนปลาย โดยมี นางสาวจุฬารัตน์ สียา เป็นอาจารย์ที่ปรึกษา
                                </p>
                                <img src="http://smd-s.kku.ac.th/home/images/smd-62/Computer_Education_Open_House_2018.jpg"
                                    width=100%>
                                <a href="https://www.facebook.com/SMD.KKU/photos/?tab=album&album_id=1936354579827618"><i
                                        class="fas fa-external-link-alt"></i></a>
                                <a
                                    href="http://smd-s.kku.ac.th/home/index.php/component/content/article/80-2012-09-14-02-08-54/421-computer-education-open-house-2018"><i
                                        class="fas fa-external-link-alt"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-3"></div>
                <?php } ?>

            </div>
        </div>
    </div>
    <?php } else {
        needLogin();
    } ?>

<?php include '../global/popup.php'; ?>
<?php include '../global/footer.php'; ?>
</body>

</html>