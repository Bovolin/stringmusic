from selenium import webdriver
from selenium.webdriver.common.keys import Keys

driver = webdriver.Chrome(executable_path=r"C:\Users\wilto\chromedriver.exe")
driver.get("http://localhost/stringmusic/index.php")
driver.implicitly_wait(3.0)

driver.find_element_by_xpath("//a[@href='login.php']").click()
driver.implicitly_wait(3.0)

# Enviar login
login = driver.find_element_by_name("emaillogar")
login.send_keys("filipe.bovolin@gmail.com")
driver.implicitly_wait(3.0)

# Enviar senha
senha = driver.find_element_by_name("senhalogar")
senha.send_keys("123123")
driver.implicitly_wait(3.0)

driver.find_element_by_xpath("//input[@class='btn solid']").click()