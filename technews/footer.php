<div id="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php
                $settings_sql    = "SELECT * FROM settings";
                ($settings_result = mysqli_query($conn, $settings_sql)) or die("Query Failed.");

                if (mysqli_num_rows($settings_result) > 0) {
                    while ($settings_row = mysqli_fetch_assoc($settings_result)) {
                        echo "<span>" . $settings_row["footerdesc"] . "</span>";
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>
</body>

</html>