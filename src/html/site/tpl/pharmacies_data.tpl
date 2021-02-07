
<table class="table" border="1" style="width:100%;text-align: center;">
    <thead>
        <tr>
            <th style="width:20%;">Pharmacies Name<br>(uuid)</th>
            <th>Cash balance</th>
            <th>Mask</th>
            <th >Purchase history</th>
        </tr>
    </thead>
    <tbody>
        <!-- START BLOCK : PHARMACIES_LIST -->
        <tr>
            <td><p>
                {pharmacies_name}<br>({pharmacies_uuid})
            </td>
            <td>
                {cash_balance}
            </td>
            <td align="center">
                <br>
                <table border="1" style="width:95%;text-align: center;">
                    <thead>
                        <tr>
                            <th>Name<br>(uuid)</th>
                            <th>Color</th>
                            <th>Per</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- START BLOCK : MASK_LIST -->
                        <tr>
                            <td>
                                {mask_name}<br>({uuid})
                            </td>
                            <td>
                                {mask_color}
                            </td>
                            <td>
                                {mask_per}
                            </td>
                            <td>
                                {mask_price}
                            </td>
                        </tr>
                        <!-- END BLOCK : MASK_LIST -->
                    </tbody>
                </table>
                <br>
            </td>
            <td align="center">
                <br>
                <table border="1" style="width:95%;text-align: center;">
                    <thead>
                        <tr>
                            <th style="width:30%;">Time</th>
                            <th>Name</th>
                            <th>Pharmacies</th>
                            <th>Amounts</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- START BLOCK : SELL_LIST -->
                        <tr>
                            <td>
                                {shell_time}
                            </td>
                            <td>
                                {shell_name}
                            </td>
                            <td>
                                {shell_phar}
                            </td>
                            <td>
                                {shell_amounts}
                            </td>
                        </tr>
                        <!-- END BLOCK : SELL_LIST -->
                    </tbody>
                </table>
                <br>
            </td>
        </tr>
        <!-- END BLOCK : PHARMACIES_LIST -->
    </tbody>
</table>