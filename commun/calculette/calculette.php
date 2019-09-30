        <table class="calculatrice" id="calc" style="text-align:center">
            <tr>
                <td colspan="4" class="calc_td_resultat" style="text-align:center">
                    <input type="text" readonly="readonly" name="calc_resultat" id="calc_resultat" class="text ui-widget-content ui-corner-all" style="width:275px;font-size:14px;text-align:right;" onkeydown="javascript:key_detect_calc('calc',event);" />
                </td>
            </tr>
            <tr>
                <td class="calc_td_btn">
                        <input type="button" class="calc_btn ui-widget ui-widget-content ui-corner-all ui-widget-header" value="CE" onclick="javascript:f_calc('calc','ce');" />
                </td>
                <td class="calc_td_btn">
                        <input type="button" class="calc_btn ui-widget ui-widget-content ui-corner-all ui-widget-header" value="DEL" onclick="javascript:f_calc('calc','nbs');" />
                </td>
                <td class="calc_td_btn">
                        <input type="button" class="calc_btn ui-widget ui-widget-content ui-corner-all ui-widget-header ui-state-default" value="%" onclick="javascript:f_calc('calc','%');" />
                </td>
                <td class="calc_td_btn">
                        <input type="button" class="calc_btn ui-widget ui-widget-content ui-corner-all ui-widget-header ui-state-default" value="+" onclick="javascript:f_calc('calc','+');" />
                </td>
            </tr>
            <tr>
                <td class="calc_td_btn">
                        <input type="button" class="calc_btn ui-widget ui-widget-content ui-corner-all" style="font-weight:bold" value="7" onclick="javascript:add_calc('calc',7);" />
                </td>
                <td class="calc_td_btn">
                        <input type="button" class="calc_btn ui-widget ui-widget-content ui-corner-all" style="font-weight:bold" value="8" onclick="javascript:add_calc('calc',8);" />
                </td>
                <td class="calc_td_btn">
                        <input type="button" class="calc_btn ui-widget ui-widget-content ui-corner-all" style="font-weight:bold" value="9" onclick="javascript:add_calc('calc',9);" />
                </td>
                <td class="calc_td_btn">
                        <input type="button" class="calc_btn ui-widget ui-widget-content ui-corner-all ui-widget-header ui-state-default" value="-" onclick="javascript:f_calc('calc','-');" />
                </td>
            </tr>
                        <tr>
                <td class="calc_td_btn">
                        <input type="button" class="calc_btn ui-widget ui-widget-content ui-corner-all" style="font-weight:bold" value="4" onclick="javascript:add_calc('calc',4);" />
                </td>
                <td class="calc_td_btn">
                        <input type="button" class="calc_btn ui-widget ui-widget-content ui-corner-all" style="font-weight:bold" value="5" onclick="javascript:add_calc('calc',5);" />
                </td>
                <td class="calc_td_btn">
                        <input type="button" class="calc_btn ui-widget ui-widget-content ui-corner-all" style="font-weight:bold" value="6" onclick="javascript:add_calc('calc',6);" />
                </td>
                <td class="calc_td_btn">
                        <input type="button" class="calc_btn ui-widget ui-widget-content ui-corner-all ui-widget-header ui-state-default" value="x" onclick="javascript:f_calc('calc','*');" />
                </td>
            </tr>
            <tr>
                <td class="calc_td_btn">
                        <input type="button" class="calc_btn ui-widget ui-widget-content ui-corner-all" style="font-weight:bold" value="1" onclick="javascript:add_calc('calc',1);" />
                </td>
                <td class="calc_td_btn">
                        <input type="button" class="calc_btn ui-widget ui-widget-content ui-corner-all" style="font-weight:bold" value="2" onclick="javascript:add_calc('calc',2);" />
                </td>
                <td class="calc_td_btn">
                        <input type="button" class="calc_btn ui-widget ui-widget-content ui-corner-all" style="font-weight:bold" value="3" onclick="javascript:add_calc('calc',3);" />
                </td>
                <td class="calc_td_btn">
                        <input type="button" class="calc_btn ui-widget ui-widget-content ui-corner-all ui-widget-header ui-state-default" value="&divide;" onclick="javascript:f_calc('calc','/');" />
                </td>
            </tr>
            <tr>
                <td class="calc_td_btn">
                        <input type="button" class="calc_btn ui-widget ui-widget-content ui-corner-all" style="font-weight:bold" value="0" onclick="javascript:add_calc('calc',0);" />
                </td>
                <td class="calc_td_btn">
                        <input type="button" class="calc_btn ui-widget ui-widget-content ui-corner-all" style="font-weight:bold" value="&plusmn;" onclick="javascript:f_calc('calc','+-');" />
                </td>
                <td class="calc_td_btn">
                        <input type="button" class="calc_btn ui-widget ui-widget-content ui-corner-all" style="font-weight:bold" value="," onclick="javascript:add_calc('calc','.');" />
                </td>
                <td class="calc_td_btn">
                        <input type="button" class="calc_btn ui-widget ui-widget-content ui-corner-all ui-widget-header" value="=" onclick="javascript:f_calc('calc','=');" />
                </td>
            </tr>
        </table>
        <script type="text/javascript">
                document.getElementById('calc').onload=initialiser_calc('calc');
        </script>