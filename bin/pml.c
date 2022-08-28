// for printf and fileio
#include <stdio.h>
#include <stdlib.h>

// for mkdir and friends:
#include <sys/stat.h>
#include <sys/types.h>

// for errno and strcmp
#include <errno.h>
#include <string.h>

int main(int argc, char *argv[])
{
    char string[1024] = {0};

    if (argc < 2) {
        printf("Usage: %s new <modname>\n", argv[0]);
        return 1;
    }

    if (argc == 3 && strcmp(argv[1], "new") == 0) {
        printf("initializing project %s\n", argv[2]);
        // make a new directory by the name of the module
        if (mkdir(argv[2], 0755)) {
            printf("error: %s\n", strerror(errno));
            return 1;
        }
        if (errno == EEXIST) {
            printf("directory %s already exists\n", argv[2]);
            return 1;
        }
        // make a new directory called "test" inside the new directory
        snprintf(string, 1024, "%s/test", argv[2]);
        if (mkdir(string, 0755)) {
            printf("error creating directory %s\n", string);
            return 1;
        }

        // put a rundev.sh file in there and make it executable
        memset(string, 0, 1024);
        snprintf(string, 1024, "%s/test/rundev.sh", argv[2]);
        FILE *f = fopen(string, "w");
        if (f == NULL) {
            printf("error creating rundev.sh\n");
            return 1;
        }
        fprintf(f, "#!/bin/sh\n");
        fprintf(f, "./test/test\n");
        fclose(f);
        memset(string, 0, 1024);
        snprintf(string, 1024, "%s/test/rundev.sh", argv[2]);
        chmod(string, 0755);

        // now put a test.php file in there
        memset(string, 0, 1024);
        snprintf(string, 1024, "%s/test/test.php", argv[2]);
        f = fopen(string, "w");
        if (f == NULL) {
            printf("error creating test.php\n");
            return 1;
        }
        fprintf(f, "<?php\n");
        fprintf(f, "echo \"Hello World!\";\n");
        fprintf(f, "?>\n");
        fclose(f);

        printf("done\n");
    } else {
        printf("Usage: %s new <modname>\n", argv[0]);
        return 1;
    }

    return 0;
}
