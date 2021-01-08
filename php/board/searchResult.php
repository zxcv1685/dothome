<?php
    include '../connect/connect.php';
    include '../connect/session.php';
    include '../connect/checkSession.php';
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="1rrock">
    <meta name="description" content="1rrock 포트폴리오 사이트입니다.">
    <meta name="keywords" content="웹표준, 웹접근성, 사이트만들기, 포트폴리오, 1rrock">
    <title>1rrock Portfolio</title>
    
    <!-- CSS Style -->
    <link rel="stylesheet" href="../assets/css/reset.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    
    <!-- webfonts -->
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:700,900" rel="stylesheet">
</head>
<body>
    <!-- skip -->
    <div id="skip">
        <a href="#mainCont">콘텐츠 바로가기</a>
    </div>
    <!-- //skip -->
    
    <!-- header -->
    <div id="header">
        <?php
            include '../component/header.php';
        ?>    
    </div>
    <!-- //header -->
    
    <main>
        <!-- boardCont -->
        <section id="boardCont">
            <div class="container">
                <div class="listBoard">
                    <h2>검색한 결과입니다.</h2>
                    <div class="listSearch">
                        <a class="form-btn black" href="board.php">목록 보기</a>
                    </div>
                    <!-- //listSearch -->
                    <div class="listTable">
                        <table class="table">
                            <colgroup>
                                <col style="width: 10%">
                                <col style="width: 65%">
                                <col style="width: 10%">
                                <col style="width: 15%">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>번호</th>
                                    <th>제목</th>
                                    <th>등록자</th>
                                    <th>등록일</th>
                                </tr>
                            </thead>
                            <tbody>
<?php
    if( isset($_GET['page'])){
        $page = (int) $_GET['page'];
    } else {
        $page = 1;
    }
    $numView = 10;
    $viewLimit = ($numView * $page) - $numView;

    $searchKeyword = $dbConnect -> real_escape_string($_POST['searchKeyword']);
    $searchOption = $dbConnect -> real_escape_string($_POST['searchOption']);

    if( $searchKeyword =='' || $searchKeyword == null ){
        echo "검색어가 없습니다.";
        exit;
    }

    switch ($searchOption){
        case 'title':
        case 'content':
        case 'tandc':
        case 'torc':
            break;
        default:
            echo "검색 옵션이 없습니다.";
            exit;
            break;
    }

    // $sql = "SELECT b.boardID, b.boardTitle, b.boardContent, m.youNickName, b.regTime FROM myBoard b JOIN myMember m ON (m.memberID = b.memberID) WHERE b.boardTitle LIKE '%{$searchKeyword}%' ";
    // $sql = "SELECT b.boardID, b.boardTitle, b.boardContent, m.youNickName, b.regTime FROM myBoard b JOIN myMember m ON (m.memberID = b.memberID) WHERE b.boardContent LIKE '%{$searchKeyword}%' ";
    // $sql = "SELECT b.boardID, b.boardTitle, b.boardContent, m.youNickName, b.regTime FROM myBoard b JOIN myMember m ON (m.memberID = b.memberID) WHERE b.boardTitle LIKE '%{$searchKeyword}%' AND b.boardContent LIKE '%{$searchKeyword}%' ";
    // $sql = "SELECT b.boardID, b.boardTitle, b.boardContent, m.youNickName, b.regTime FROM myBoard b JOIN myMember m ON (m.memberID = b.memberID) WHERE b.boardTitle LIKE '%{$searchKeyword}%' OR b.boardContent LIKE '%{$searchKeyword}%' ";

    $sql = "SELECT b.boardID, b.boardTitle, b.boardContent, m.youNickName, b.regTime FROM myBoard b JOIN myMember m ON (m.memberID = b.memberID) ";

    switch ($searchOption){
        case 'title':
            $sql .= "WHERE b.boardTitle LIKE '%{$searchKeyword}%'";
            break;
        case 'content':
            $sql .= "WHERE b.boardContent LIKE '%{$searchKeyword}%'";
            break;
        case 'tandc':
            $sql .= "WHERE b.boardTitle LIKE '%{$searchKeyword}%' AND b.boardContent LIKE '%{$searchKeyword}%'";
            break;
        case 'torc':
            $sql .= "WHERE b.boardTitle LIKE '%{$searchKeyword}%' OR b.boardContent LIKE '%{$searchKeyword}%'";
            break;
    }
    $sql .= "LIMIT {$viewLimit}, {$numView}";

    $result = $dbConnect -> query($sql);

    if( $result ){
        $dataCount = $result -> num_rows;

        if( $dataCount > 0 ){
            for( $i=1; $i<=$dataCount; $i++ ){
                $memberInfo = $result -> fetch_array(MYSQLI_ASSOC);
                echo "<tr>";
                echo "<td>".$memberInfo['boardID']."</td>"; 
                echo "<td class='left'><a href='viewBoard.php?boardID={$memberInfo['boardID']}'>".$memberInfo['boardTitle']."</a></td>";
                echo "<td>".$memberInfo['youNickName']."</td>"; 
                echo "<td>".date('Y-m-d H:i', $memberInfo['regTime'])."</td>"; 
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>게시글이 없습니다.</td></tr>";
        }
    } else {
        echo "에러발생 : 관리자에게 문의하세요.";
        exit;
    }

?>
                                <!-- <tr>
                                    <td>1</td>
                                    <td>안녕하세요</td>
                                    <td>웹스</td>
                                    <td>2021-01-07</td>
                                </tr> -->
                            </tbody>
                        </table>
                    </div>
                    <!-- //listTable -->
                    <?php
                        include 'pagination.php';
                    ?>
                </div>
            </div>
        </section>
        <!-- //boardCont -->
    </main>
    <!-- <?php
        echo '<pre>';
        var_dump($_SESSION);
        echo '</pre>';
    ?> -->
</body>
</html>