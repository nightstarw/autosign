from selenium import webdriver
import subprocess
import time
import sys
 
driver = webdriver.Firefox()
driver.get("http://qzone.qq.com")

driver.switch_to_frame('login_frame')
driver.find_element_by_id('switcher_plogin').click() 
driver.find_element_by_id('u').clear()
driver.find_element_by_id('u').send_keys('qq号')
driver.find_element_by_id('p').clear()
driver.find_element_by_id('p').send_keys('qq密码')
driver.find_element_by_id('login_button').click()
 
skey = driver.get_cookie('skey')
skey = skey['value']
print(skey)
driver.close()

proc = subprocess.Popen(['php -f ./qqQun.php sign '+ skey],shell=True,stdout=subprocess.PIPE);

response = proc.stdout.read()
