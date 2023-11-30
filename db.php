<!-- 11/25 -->
<?php
date_default_timezone_set("Asia/Taipei");
session_start();
class DB
{

    protected $dsn = "mysql:host=localhost;charset=utf8;dbname=school";
    protected $pdo;
    protected $table;

    public function __construct($table)
    {
        $this->table = $table;
        $this->pdo = new PDO($this->dsn, 'root', '');
    }

// 定義一個名為 all 的方法，用於取得資料表中的所有資料
    function all($where = '', $other = ''){
        // 初始化 SQL 查詢字串，選擇所有欄位
        $sql = "select * from `$this->table` ";

        // 檢查資料表名稱是否存在並不為空
        if (isset($this->table) && !empty($this->table)) {

            // 檢查傳入的條件是否為陣列型態
            if (is_array($where)) {

                // 檢查陣列是否為非空
                if (!empty($where)) {
                    // 將陣列轉換成 SQL 條件語句
                    foreach ($where as $col => $value) {
                        $tmp[] = "`$col`='$value'";
                    }
                    // 將條件語句加入到 SQL 查詢中
                    $sql .= " where " . join(" && ", $tmp);
                }
            } else {
                // 若條件不是陣列，直接將其加入到 SQL 查詢中
                $sql .= " $where";
            }

            // 將其他條件加入到 SQL 查詢中
            $sql .= $other;

            // 使用 PDO 查詢資料表，取得所有符合條件的資料並以關聯陣列形式返回
            $rows = $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
            return $rows;
        } else {
            // 若資料表名稱不存在或為空，輸出錯誤訊息
            echo "錯誤:沒有指定的資料表名稱";
        }
    }
    // 定義一個名為 count 的方法，用於計算符合條件的資料總數
function count($where = '', $other = ''){
    // 初始化 SQL 查詢字串，計算資料表中符合條件的總數
    $sql = "select count(*) from `$this->table` ";

    // 檢查資料表名稱是否存在並不為空
    if (isset($this->table) && !empty($this->table)) {

        // 檢查傳入的條件是否為陣列型態
        if (is_array($where)) {

            // 檢查陣列是否為非空
            if (!empty($where)) {
                // 將陣列轉換成 SQL 條件語句
                foreach ($where as $col => $value) {
                    $tmp[] = "`$col`='$value'";
                }
                // 將條件語句加入到 SQL 查詢中
                $sql .= " where " . join(" && ", $tmp);
            }
        } else {
            // 若條件不是陣列，直接將其加入到 SQL 查詢中
            $sql .= " $where";
        }

        // 將其他條件加入到 SQL 查詢中
        $sql .= $other;

        // 使用 PDO 查詢資料表，取得符合條件的資料總數
        $rows = $this->pdo->query($sql)->fetchColumn();
        return $rows;
    } else {
        // 若資料表名稱不存在或為空，輸出錯誤訊息
        echo "錯誤:沒有指定的資料表名稱";
    }
}
   // 定義一個名為 find 的方法，用於尋找符合指定條件的單一資料
function find($id){
    // 初始化 SQL 查詢字串，選擇所有欄位
    $sql = "select * from `$this->table` ";

    // 檢查傳入的 $id 參數是否為陣列型態
    if (is_array($id)) {
        // 若是陣列，將其轉換成 SQL 條件語句
        foreach ($id as $col => $value) {
            $tmp[] = "`$col`='$value'";
        }
        // 將條件語句加入到 SQL 查詢中
        $sql .= " where " . join(" && ", $tmp);
    } else if (is_numeric($id)) {
        // 若 $id 為數字，直接指定查詢條件為該 id
        $sql .= " where `id`='$id'";
    } else {
        // 若傳入的參數不是數字或陣列，輸出錯誤訊息
        echo "錯誤:參數的資料型態應為數字或陣列";
    }

    // 使用 PDO 查詢資料表，取得符合條件的單一資料並以關聯陣列形式返回
    $row = $this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
    return $row;
}
 // 定義一個名為 save 的方法，用於新增或更新資料表中的資料
function save($array){
    // 檢查傳入的陣列是否包含 'id' 鍵（表示要進行資料更新）
    if (isset($array['id'])) {
        // 初始化 SQL 更新語句，設定更新的資料欄位
        $sql = "update `$this->table` set ";

        // 檢查是否有要更新的欄位資料
        if (!empty($array)) {
            // 遍歷欄位與值，組成 SET 子句的 SQL 語句
            foreach ($array as $col => $value) {
                $tmp[] = "`$col`='$value'";
            }
        } else {
            // 若欄位陣列為空，輸出錯誤訊息
            echo "錯誤:缺少要編輯的欄位陣列";
        }

        // 將 SET 子句加入 SQL 更新語句，並設定更新條件為指定的 ID
        $sql .= join(",", $tmp);
        $sql .= " where `id`='{$array['id']}'";
    } else {
        // 若陣列中不包含 'id' 鍵，表示要進行資料新增
        // 初始化 SQL 新增語句，設定新增的欄位與值
        $sql = "insert into `$this->table` ";
        $cols = "(`" . join("`,`", array_keys($array)) . "`)";
        $vals = "('" . join("','", $array) . "')";

        // 將新增的欄位與值加入 SQL 新增語句
        $sql .= $cols . " values " . $vals;
    }

    // 使用 PDO 執行 SQL 語句，返回受影響的資料列數量
    return $this->pdo->exec($sql);
}

    // 定義一個名為 del 的方法，用於刪除資料表中的資料
function del($id){
    // 初始化 SQL 刪除語句，設定刪除的條件
    $sql = "delete from `$this->table` where ";

    // 檢查傳入的 ID 參數型態
    if (is_array($id)) {
        // 若 ID 為陣列，表示要進行條件式刪除
        foreach ($id as $col => $value) {
            $tmp[] = "`$col`='$value'";
        }
        // 將陣列中的條件用 "AND" 連接，加入 SQL 刪除語句
        $sql .= join(" && ", $tmp);
    } else if (is_numeric($id)) {
        // 若 ID 為數字，表示要刪除指定 ID 的資料
        $sql .= " `id`='$id'";
    } else {
        // 若 ID 既非陣列也非數字，輸出錯誤訊息
        echo "錯誤:參數的資料型態比須是數字或陣列";
    }
    //echo $sql;

    // 使用 PDO 執行 SQL 刪除語句，返回受影響的資料列數量
    return $this->pdo->exec($sql);
}
// 定義一個名為 q 的方法，用於執行任意 SQL 查詢語句
function q($sql){
    // 使用 PDO 的 query 方法執行傳入的 SQL 查詢語句
    // 並以 fetchAll 方法取得所有結果的關聯陣列形式
    return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}
}
function dd($array){
    echo "<pre>";
    print_r($array);
    echo "</pre>";
}


$student = new DB('students');
// $rows = $student->q("select * from `students`");
$rows = $student->count();
dd($rows);
