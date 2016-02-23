<?php
/**
 * Email Footer
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


// For gmail compatibility, including CSS styles in head/body are stripped out therefore styles need to be inline. These variables contain rules which are added to the template inline.
$template_footer = "
	background-color:#eeeeee;
	border-top:0;
";

$credit = "
	border:0;
	color: #757575;
	font-family: Helvetica,Geneva,Verdana,sans-serif;
	font-size: 11px;
    line-height: 16px;
    padding: 10px 0 10px 20px;
    text-align: center;
    vertical-align: top;
    width: 660px;
}
";
?>
															</div>
														</td>
                                                    </tr>
                                                </table>
                                                <!-- End Content -->
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- End Body -->
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                	<td>
                        <!-- Footer -->
                        <table border="0" cellpadding="0" cellspacing="0" align="center" width="660" id="template_footer" style="<?php echo $template_footer; ?>">
                            <tr>
                                <td valign="top">
                                    <table border="0" cellpadding="10" cellspacing="0" width="100%">
                                        <tr>
                                            <td colspan="2" align="center" width="660" valign="middle" id="credit" style="<?php echo $credit; ?>">
                                                <?php echo wpautop( wp_kses_post( wptexturize( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) ) ) ); ?>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <!-- End Footer -->
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>