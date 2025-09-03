# Prompt Merger
***


## Description
The Stable Diffusion Image Generator has a sophisticated system for combining prompts from multiple .txt files.  
To build your own prompt merger, create a subdirectory named [DIRECTORY_NAME] here.  
The subdirectory [DIRECTORY_NAME] should not contain any spaces.  
Next, create several .txt files in this subdirectory.  
The prompt merger will go through these .txt files one by one, randomly selecting a line from each file to create a prompt.  
The files are loaded and processed in alphabetical order, so it’s best to start the .txt files with a number.  

Example:
- 001_style.txt
- 002_object.txt
- 003_object_details.txt
- 004_action.txt
- 005_background.txt
- 006_details.txt

Configure in config.local.php:
- $this->prompt = '[DIRECTORY_NAME]'

Prompt files for prompt merging are best created using the web application.
***