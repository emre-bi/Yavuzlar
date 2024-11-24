package main

import (
	"bufio"
	"fmt"
	"io"
	"log"
	"net/http"
	"os"
	"strconv"
	"strings"

	"github.com/PuerkitoBio/goquery"
)

type ExtractFunc func(string) []string

func showMenu() {
	fmt.Println("  -1: The Hacker News")
	fmt.Println("  -2: bleeping computer")
	fmt.Println("  -3: packet storm security")
	fmt.Println("  -4: quit")
	fmt.Print("Enter an Option >>>>>>>>>>>> ")
}

func scrapeAndSave(url, outputFile string, extract ExtractFunc) {
	body, err := fetchHTML(url)
	if err != nil {
		log.Println("Error fetching data:", err)
		return
	}

	parsedBody := extract(body)

	err = saveToFile(outputFile, parsedBody)
	if err != nil {
		log.Println("Error saving to file:", err)
	} else {
		fmt.Printf("Data saved to %s\n", outputFile)
	}
}

func fetchHTML(url string) (string, error) {
	req, err := http.NewRequest("GET", url, nil)
	if err != nil {
		fmt.Printf("Failed to create request: %v\n", err)
		return "", err
	}

	req.Header.Set("User-Agent", "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36")

	client := &http.Client{}
	resp, _ := client.Do(req)
	if resp.StatusCode != 200 {
		return "", fmt.Errorf("error: status code = %d", resp.StatusCode)
	}

	defer resp.Body.Close()

	bodyBytes, err := io.ReadAll(resp.Body)
	if err != nil {
		return "", err
	}

	return string(bodyBytes), nil
}

func main() {
	for {
		showMenu()
		choice := readChoice()

		switch choice {
		case -1:
			scrapeAndSave("https://thehackernews.com/", "hackernews.txt", extractHackerNews)
		case -2:
			scrapeAndSave("https://www.bleepingcomputer.com/", "bleepingcomputer.txt", extractBleepingComputer)
		case -3:
			scrapeAndSave("https://packetstormsecurity.com/", "packetstormsecurity.txt", extractPacketStormSecurity)
		case -4:
			return
		default:
			fmt.Println("Please select one of the available options")
		}
	}
}

func readChoice() int {
	reader := bufio.NewReader(os.Stdin)
	input, err := reader.ReadString('\n')
	if err != nil {
		log.Println("Error reading input:", err)
		return 0
	}

	choice, err := strconv.Atoi(strings.TrimSpace(input))
	if err != nil {
		log.Println("Invalid input, expected a number")
		return 0
	}
	return choice
}

func saveToFile(filename string, lines []string) error {
	file, err := os.Create(filename)
	if err != nil {
		return err
	}
	defer file.Close()

	for _, line := range lines {
		_, err := file.WriteString(line + "\n")
		if err != nil {
			return err
		}
	}
	return nil
}

func extractHackerNews(body string) []string {
	var data []string
	doc, err := goquery.NewDocumentFromReader(strings.NewReader(body))
	if err != nil {
		log.Printf("Error parsing HTML: %v", err)
		return data
	}

	doc.Find(".body-post").Each(func(i int, s *goquery.Selection) {
		title := s.Find("h2").Text()
		description := s.Find(".home-desc").Text()
		date := s.Find(".h-datetime").Text()
		if title != "" && description != "" && date != "" {
			data = append(data, fmt.Sprintf("Title: %s\nDescription: %s\nDate: %s\n", title, description, date))
		}
	})
	return data
}

func extractBleepingComputer(body string) []string {
	var data []string
	doc, err := goquery.NewDocumentFromReader(strings.NewReader(body))
	if err != nil {
		log.Printf("Error parsing HTML: %v", err)
		return data
	}

	doc.Find(".bc_latest_news_text").Each(func(i int, s *goquery.Selection) {
		title := s.Find("h4 a").Text()
		description := s.Find("p").Text()
		date := s.Find(".bc_news_date").Text()
		if title != "" && description != "" && date != "" {
			data = append(data, fmt.Sprintf("Title: %s\nDescription: %s\nDate: %s\n", title, description, date))
		}
	})
	return data
}

func extractPacketStormSecurity(body string) []string {
	var data []string
	doc, err := goquery.NewDocumentFromReader(strings.NewReader(body))
	if err != nil {
		log.Printf("Error parsing HTML: %v", err)
		return data
	}
	doc.Find("dl").Each(func(i int, s *goquery.Selection) {
		title := s.Find("dt a").Text()
		description := s.Find(".detail p").Text()
		date := s.Find(".datetime a").Text()
		if title != "" && description != "" && date != "" {
			data = append(data, fmt.Sprintf("Title: %s\nDescription: %s\nDate: %s\n", title, description, date))
		}
	})
	return data
}
