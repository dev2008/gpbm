<?php
/*
***********************************************************************************
DaDaBIK (DaDaBIK is a DataBase Interfaces Kreator) https://dadabik.com/
Copyright (C) 2001-2026 Eugenio Tacchini

This program is distributed "as is" and WITHOUT ANY WARRANTY, either expressed or implied, without even the implied warranties of merchantability or fitness for a particular purpose.

This program is distributed under the terms of the DaDaBIK license, which is included in this package (see dadabik_license.txt). For all the details see dadabik_license.txt.

If you are unsure about what you are allowed to do with this license, feel free to contact info@dadabik.com
***********************************************************************************
*/
?>
<style>
.personal-letter {
  max-width: 760px;
  margin: 42px auto 80px;
  padding: 46px 54px 42px;
  background: #fffdf7;
  border: 1px solid #eadfc8;
  border-radius: 14px;
  box-shadow: 0 18px 45px rgba(0,0,0,.08);
  font-family: Georgia, "Times New Roman", serif;
  color: #1f1f1f;
}

.personal-letter .letter-kicker {
  font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
  font-size: 13px;
  font-weight: 700;
  letter-spacing: .08em;
  text-transform: uppercase;
  color: #6f7d2d;
  margin-bottom: 12px;
}

.personal-letter h1 {
  font-size: 34px;
  line-height: 1.18;
  font-weight: 600;
  margin: 0 0 26px;
}

.personal-letter p {
  font-size: 18px;
  line-height: 1.68;
  margin: 0 0 18px;
}

.signature {
  margin-top: 10px;
  font-size: 38px;
  line-height: 1;
  font-style: italic;
}

.signature-meta {
  margin-top: 8px;
  font-size: 15px;
  line-height: 1.45;
  color: #555;
}

.letter-actions {
  margin-top: 34px;
  display: flex;
  align-items: center;
  gap: 18px;
}

.review-button {
  display: inline-block;
  padding: 12px 18px;
  border-radius: 8px;
  background: #2f6fb5;
  color: #fff;
  font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
  font-weight: 700;
  text-decoration: none;
}

.skip-link {
  color: #555;
  font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
  text-decoration: underline;
}

.letter-actions {
  margin-top: 34px;
}

.review-button {
  display: inline-block;
  padding: 12px 20px;
  border-radius: 8px;
  background: #2f6fb5;
  color: #fff;
  font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
  font-weight: 700;
  text-decoration: none;
}

.secondary-actions {
  margin-top: 18px;
  font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
  font-size: 14px;
  color: #777;
}

.secondary-actions a {
  color: #666;
  text-decoration: none;
}

.secondary-actions a:hover {
  text-decoration: underline;
}

.personal-letter .review-button,
.personal-letter .review-button:visited {
  display: inline-block;
  padding: 12px 20px;
  border-radius: 8px;
  background: #245f9f;
  color: #fff !important;
  font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
  font-size: 15px;
  font-weight: 700;
  text-decoration: none;
}

.personal-letter .review-button:hover {
  background: #1d4f85;
  color: #fff !important;
  text-decoration: none;
}
.info-tooltip {
  position: relative;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 17px;
  height: 17px;
  margin-left: 5px;
  border: 1px solid #b8b8b8;
  border-radius: 50%;
  color: #666;
  font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
  font-size: 11px;
  font-weight: 700;
  line-height: 1;
  cursor: help;
}

.info-tooltip:hover::after,
.info-tooltip:focus::after {
  content: attr(aria-label);
  position: absolute;
  left: 50%;
  bottom: 26px;
  transform: translateX(-50%);
  width: 280px;
  padding: 10px 12px;
  border-radius: 7px;
  background: #222;
  color: #fff;
  font-size: 13px;
  font-weight: 400;
  line-height: 1.35;
  text-align: left;
  box-shadow: 0 8px 22px rgba(0,0,0,.18);
  z-index: 20;
}

.info-tooltip:hover::before,
.info-tooltip:focus::before {
  content: "";
  position: absolute;
  left: 50%;
  bottom: 20px;
  transform: translateX(-50%);
  border-width: 6px 6px 0 6px;
  border-style: solid;
  border-color: #222 transparent transparent transparent;
  z-index: 21;
}

</style>

<div class="personal-letter">
  <div class="letter-kicker">A personal note</div>

  <h1>A small request from the founder</h1>

  <p>Hi,</p>

  <p>DaDaBIK grows mostly through word of mouth. We don't run advertising campaigns, so honest reviews on platforms such as <a href="https://www.capterra.com/p/159738/DaDaBIK/#reviews" target="_blank">Capterra</a> are very important for people evaluating the product.</p>

  <p>If DaDaBIK has been useful to you, I'd really appreciate a short review. It only takes a few minutes.</p>

  <p>Thanks for your time,</p>

  <div class="signature">Eugenio</div><br>  
  <div class="signature-meta">Eugenio Tacchini, Ph.D.<br>Founder, DaDaBIK</div>

  <div class="letter-actions">
    <a class="review-button" href="admin.php?function=leave_review" target="_blank">Leave a review on Capterra</a>
    
  </div>
  <div class="secondary-actions">
    <a href="admin.php?function=remind_review_one_month">Remind me in one month</a>
    <span> · </span>
    <a href="admin.php?function=dont_show_review_invitation_again">Don’t show this again</a><span class="info-tooltip" tabindex="0" aria-label="Use this if you prefer not to leave a review, or if you already reviewed DaDaBIK for another installation. We won’t show this request again here.">?</span>
  </div>
</div>



