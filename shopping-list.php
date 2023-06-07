<!DOCTYPE html>
<html>

<head>
    <meta charset="uft-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Lista zakupowa</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    


</head>

<body style="background-color: rgba(88, 71, 15, 1);
            background-image: linear-gradient(90deg, rgba(98, 236, 181, 1) 0%, rgba(71, 218, 202, 1) 100%);
            background-size: cover;
            background-position: right top;
            background-repeat: repeat-y;">

    <a href="index.php">Go back</a>

    <form method="post" action="shopping-list.php">


        <hr>
        <input type="text" name="product" placeholder="Produkt">
        <input type="number" name="count" placeholder="Ilość" maxlength="4">

        <input type="text" name="shop" placeholder="Sklep">

        <input type="checkbox" name="promotion">
        <br>
        <hr>
        <br>
        <button type="submit" name="Submit">Dodaj do listy</button>
        <button style="background-color: #742525; margin: 20; width: 140px;" type="submit" name="DeleteAll">Wyczyść Listę</button>
    </form>

    <?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    class Product
    {

        public $name;
        public $count;
        public $shop;
        public $promotion;

        public function __construct($name, $count, $shop, $promotion)
        {
            $this->name = $name;
            $this->count = $count;
            $this->shop = $shop;
            $this->promotion = $promotion;
        }

        public function getDetails()
        {
            return "Product: " . $this->name . ", Count: " . $this->count;
        }

        public function tableConstruct($part)
        {
            $part = "<th>{$part}</th>";
            echo $part;
            return $part;
        }

        public function getTableDetails()
        {


            return $this->tableConstruct($this->name) . $this->tableConstruct($this->count) . $this->tableConstruct($this->shop) . $this->tableConstruct($this->promotion);
        }
    }

    class ListManagment
    {

        public function ButtonSubmit()
        {


            $name = filter_input(INPUT_POST, 'product', FILTER_SANITIZE_SPECIAL_CHARS);
            $count = filter_input(INPUT_POST, 'count', FILTER_SANITIZE_SPECIAL_CHARS);
            $shop = filter_input(INPUT_POST, 'shop', FILTER_SANITIZE_SPECIAL_CHARS);
            $promotion = filter_input(INPUT_POST, 'promotion', FILTER_SANITIZE_SPECIAL_CHARS);

            if ($name != null && $count != null) {

                $newProduct = new Product($name, $count, $shop, $promotion);


                $file = 'listings_data.txt';
                $serializedDataFromFile = file_get_contents($file);

                $list = unserialize($serializedDataFromFile);


                $list[] = $newProduct;


                $serializedData = serialize($list);


                file_put_contents($file, $serializedData);

            }
        }

        public function ShowList()
        {

            $file = 'listings_data.txt';
            $serializedDataFromFile = file_get_contents($file);

            $list = unserialize($serializedDataFromFile);
            if ($list != null) {
                echo 'List:' . "<br>";
                foreach ($list as $product) {
                    echo $product->getDetails() . "<br>";
                }
            }
        }

        public function ShowTableList()
        {
            $file = 'listings_data.txt';
            $serializedDataFromFile = file_get_contents($file);
            $list = unserialize($serializedDataFromFile);

            if ($list != null) {
                echo '<table border="2px">';
                echo '<thead>';
                echo '<tr>';
                echo '<th>Produkt:</th>';
                echo '<th>Ilość:</th>';
                echo '<th>Sklep:</th>';
                echo '<th>%:</th>';
                echo '<th>Opcje:</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

                foreach ($list as $index => $product) {
                    echo '<tr>';
                    echo '<td>' . $product->name . '</td>';
                    echo '<td>' . $product->count . '</td>';
                    echo '<td>' . $product->shop . '</td>';
                    echo '<td>' . $product->promotion . '</td>';
                    echo '<td>';
                    echo '<form action="shopping-list.php" method="post">';
                    echo '<form action="shopping-list.php" method="post">';
                    echo '<input type="hidden" name="delete" value="' . $index . '">';
                    echo '<button style="background-color: #742525; width: 100%;" type="submit" name="Delete" value="' . $index . '">Delete</button>';
                    echo '</form>';
                    echo '</td>';
                    echo '</tr>';
                }

                echo '</tbody>';
                echo '</table>';
            }
        }



        public function ButtonDeleteAll()
        {
            $file = 'listings_data.txt';
            file_put_contents($file, "");
        }

        public function ButtonDeleteMarked($index)
        {
            $file = 'listings_data.txt';
            $serializedDataFromFile = file_get_contents($file);
            $list = unserialize($serializedDataFromFile);

            unset($list[$index]);
            $list = array_values($list);

            $serializedData = serialize($list);
            file_put_contents($file, $serializedData);
        }
    }



    $button = new ListManagment();
    $button->ShowTableList();


    if (isset($_POST['DeleteAll'])) {
        $button->ButtonDeleteAll();
        header("Location: shopping-list.php");
        exit;
    }

    if (isset($_POST['Submit'])) {
        $button->ButtonSubmit();
        header("Location: shopping-list.php");
        exit;
    }


    if (isset($_POST['Delete'])) {
        $index = $_POST['Delete'];
        $button->ButtonDeleteMarked($index);
        header("Location: shopping-list.php");
        exit;
    }


    


    ?>




</body>



</html>