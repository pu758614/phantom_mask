
<table class="table" border="1" style="width:80%;text-align: center;" align="center">
    <thead>
        <tr>
            <th style="width:25%;">User name<br>(uuid)</th>
            <th>Cash balance</th>
            <th>Purchase history</th>
        </tr>
    </thead>
    <tbody>
        <!-- START BLOCK : USER_LIST -->
        <tr>
            <td><p>
                {user_name}<br>({user_uuid})
            </td>
            <td>
                {cash_balance}
            </td>
            <td>

                <br>
                <table border="1" style="width:95%;text-align: center;" align="center">
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
        <!-- END BLOCK : USER_LIST -->
    </tbody>
</table>