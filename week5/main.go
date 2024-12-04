package main

import (
	"bufio"
	"fmt"
	"os"
	"strings"
	"time"
)

type User struct {
	Username string
	Email    string
	Password string
}

var users []User

var currUserUsername string

func write_into_log(attempt bool, input string) {
	file, _ := os.OpenFile("log.txt", os.O_APPEND|os.O_CREATE|os.O_WRONLY, 0644)
	defer file.Close()
	// denemenin yapıldığı zaman ve hangi inputun girildiğini kaydet
	// _, _ = file.WriteString("Appending this line.\n")
	current_time := time.Now()
	var formatted_curr_time string = fmt.Sprintf("%d/%d/%d : %d:%d:%d", current_time.Day(), current_time.Month(), current_time.Year(), current_time.Hour(), current_time.Minute(), current_time.Second())
	if attempt == true {
		var text string = fmt.Sprintf("Successfull Login with input => %s        at -> %s\n", input, formatted_curr_time)
		_, _ = file.WriteString(text)
	} else {
		var text string = fmt.Sprintf("Failed Login Attempt with input => %s         at -> %s\n", input, formatted_curr_time)
		_, _ = file.WriteString(text)
	}
}

func view_log() {
	data, _ := os.ReadFile("log.txt")
	fmt.Printf("LOG\n--------\n%s", data)
}

func get_user_input(message string) string {
	input_reader := bufio.NewReader(os.Stdin)
	fmt.Print(message)
	user_input, _ := input_reader.ReadString('\n')
	return strings.TrimSpace(user_input)
}

func add_customer() {
	var username string = get_user_input("Username: ")
	var email string = get_user_input("Email: ")
	var password string = get_user_input("Password: ")
	isExist := false

	for _, user := range users {
		if user.Username == username {
			fmt.Println("Username is used by another user, Please use a unique username!")
			isExist = true
			break
		}
	}
	if !isExist {
		users = append(users, User{Username: username, Email: email, Password: password})
		fmt.Println("User Added Succesfully!!!")
	}
}

func list_customers() {
	fmt.Println("#### USERS ####")
	for _, customer := range users {
		fmt.Printf("Username: %s\nEmail: %s\nPassword: %s\n", customer.Username, customer.Email, customer.Password)
		fmt.Println("------------------------")
	}
}

func delete_customer() {
	var username string = get_user_input("Enter username of the User to Delete: ")
	for i, user := range users {
		if user.Username == username {
			users = append(users[:i], users[i+1:]...)
			fmt.Println("User is Deleted Successfully!!!")
		}
	}
}

func resetPassword() {
	var password_reset_input string = get_user_input("Please enter your new password:  ")

	for i, user := range users {
		if user.Username == currUserUsername {
			users[i].Password = password_reset_input
			fmt.Println("Password reset is completed Successfully, your new password is ", password_reset_input)
		}
	}

}

func showProfile() {
	fmt.Println("Profile")
	for _, user := range users {
		fmt.Println("----------------")
		fmt.Printf("Username: %s\nEmail Address: %s\nPassword: %s\n", user.Username, user.Email, user.Password)
		fmt.Println("----------------")
	}
}

func userLogin() bool {
	var user_login_username_input string = get_user_input("Welcome! Please enter your Username: ")
	var user_login_password_input string = get_user_input("Please enter your Password: ")
	login_input := user_login_username_input + " : " + user_login_password_input

	for _, user := range users {
		if user.Username == user_login_username_input {
			if user.Password == user_login_password_input {
				currUserUsername = user.Username
				write_into_log(true, login_input)
				fmt.Println("Login is successfull")
				return true
			}
		}
	}
	write_into_log(false, login_input)
	fmt.Println("Wrong username or password")
	return false
}

// ################## _______________------------------______________  ###########################
// ################## _______________------------------______________  ###########################
// ################## _______________------------------______________  ###########################
// ################## _______________------------------______________  ###########################
func main() {
	users = append(users, User{Username: "test", Email: "test@test.test", Password: "test"})

	for {
		var login_input string = get_user_input("Please enter:\n    '0' for customer login\n    '1' for admin login\n>>>>>>>>>>>>> ")
		if login_input == "0" || login_input == "1" {

			if login_input == "0" {
				fmt.Println("Default Customer Credentials -> test : test")
				loginSuccessfull := userLogin()
				if loginSuccessfull {
					for {
						var customer_option_input string = get_user_input("Please enter:\n    'a' to view your profile\n    'b' to change your password\n    'q' to return back to the login\n>>>>>>>>>>>>> ")

						if customer_option_input == "a" || customer_option_input == "b" || customer_option_input == "q" {
							if customer_option_input == "a" {
								showProfile()
							} else if customer_option_input == "b" {
								resetPassword()
							} else {
								break
							}
						} else {
							fmt.Println("Please enter a or b or q")
							continue
						}
					}
				}
			} else {
				write_into_log(true, login_input)
				fmt.Println("You logged in as Admin!")
				for {
					var admin_option_input string = get_user_input("Please enter:\n    'a' to Add Customer\n    'b' to Delete Customer\n    'c' to View Log Records\n    'd' to List Users\n    'q' to return back to the login\n>>>>>>>>>>>>> ")

					if admin_option_input == "a" || admin_option_input == "b" || admin_option_input == "c" || admin_option_input == "d" || admin_option_input == "q" {
						if admin_option_input == "a" {
							add_customer()
						} else if admin_option_input == "b" {
							delete_customer()
						} else if admin_option_input == "c" {
							view_log()
						} else if admin_option_input == "d" {
							list_customers()
						} else {
							break
						}
					} else {
						fmt.Println("Please enre a or b or c or d")
						continue
					}
				}
			}

		} else {
			write_into_log(false, login_input)
			fmt.Println("Please enter 0 or 1 to login!")
			continue
		}
	}
}
