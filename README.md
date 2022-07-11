* Reducing entanglement / Separation of concerns
    ** see below

* Easy Extensibility (Open/Closed Principle)

    * Extend the system without touching any pre-existing code: (check)

        * add plugin UIs to menus using system registration functions (check)

        * modules can request your plugin's operation without
          needing to explicitly import your plugin. The system will find and
          dynamically load the appropriate driver when needed. (check)

        * Avoid loading excessive code (check)

            * The system will find and dynamically load the appropriate driver when
              needed, minimizing the effect that syntax or initialization
              errors will have on parts of the system that don't need the
              code. This is in contrast to functions_live, where a great many
              functions are defined and initialized even for requests that do
              not use them.

        * you can write your module with minimal knowledge of the system's
          inner workings. This means new developers can become productive very
          quickly. (check)

    * all your code is in one place (check)

        * plugins are self-contained projects that can be made and tested
          in a local environment, without needing to run on the target
          system. (check)

    * Since your module is its own project, you can manage and organize the
      code however you see fit: (check)
        * Track your module in its own version control repository. (check)
        * Create a deployment process unique to that project. (check)
        * Modules can choose to ship with their own library dependencies,
          avoiding version conflicts. (check)

* Fault tolerance

    * fallback if multiple drivers fulfill the same responsibility

        * the first to register for "Logging" becomes the default logger, but a
          fallback logger can be registered with lower priority. A warning
          will be kept for troubleshooting support. (check)

        * failure to find an appropriate provider will result in an error hook
          being dispatched, which can then be handled by another plugin.

    * safe mode and recovery mode (check)

        * if a module breaks the system to the point where you can't even
          disable it from the UI, you can get in through a recovery mode, which
          employs a minimal runtime that allows you to disable plugins, change
          their settings, or revert their configurations to a restore point. (check)

    * if something is not enabled, events will safely dispatch and continue (check)

        * Dispatch events / hooks are the safest way to hook into plugin
          functionality, and any system can hook into any other system,
          further separating concerns. (check)

* Easy debugging (check)
    * enable or disable plugins for troubleshooting (check)
    * loggable plugin events (check)


================================================

Open/Closed Principle: "Open for extension, closed for modification"
A software platform should not need its code to be altered in any kind of way in order to extend its functionality.
There are several great examples of this principle in the wild.

* Operating systems allow you to make the OS more useful with every program and driver you write, and you don't need to recompile the OS to do it.
* Video game engines allow you to add new video game entities (levels, characters, logic, etc) without having to alter the game engine source code.
* Many CMS platforms, such as Wordpress, allow you to write plugins that don't need the base installation of Wordpress to be altered in any kind of way.
* Even in digital hardware, you can add new hardware without needing to change
  the existing hardware: think USB devices plugging into a motherboard, or
  putting a new video card into the PCI slot of your motherboard.

In this series, we'll take a look at some of these different modular patterns
and explore the pros and cons of each in-depth. Hopefully by the end of it,
you'll have a very practical, general view of what modularity is, and how it
can be useful in the projects that you are developing.

In all of these examples, modules are typically only useful because they can
interoperate with the host platform and its other components. Therefore, the
most important property of modular design is communication.  In fact, as you
study these modular platforms closely, you'll probably realize that the
communication environment is the basis of the platform itself. In other words,
how modules handle input, output, or side-effects defines the plugin
architecture.

DOS Modularity story
    pros
    lol
    cons
        * Any time a program wants to do something, it has to implement it
          within itself.
        * It's almost hard to really call it an operating system, with the OS
          acting more like a library, with some bootstrapping utility to load
          new programs. Beyond that, it just provides some system calls, but
          the operating system isn't monitoring the application in any kind of
          way, and the application can overwrite the system calls if it wants
          to, essentially modifying or replacing the host platform with itself.

Linux modularity story

    Executables
        Lifecycle methods (_start, signal traps for exit)
        Interrupts
        Types of executables
            Services - Long running
            Applications - Short running

    Shared Libraries
    Kernel Modules
        Block Devices

    Communications
        System Calls - for interacting with the operating system directly
        Signals (SIGTERM, SIGINT, SIGKILL, ...etc) - can be sent from other systems
        Unix and INET Sockets - multicast, broadcast, groupcast, etc...
            UDP & TCP
            Pipes, FIFOs...
            Arbitrary file descriptors
        SysV Message Queues
        Shared Memory


    A lot of these mechanisms have overlapping functionality. A simpler operating
    system could be conceived in which the many different communication vectors are
    replaced by fewer, more flexible alternatives.


    Pros:
        - It's easy to create a new module (program)
        - Most modules get everything they need from the operating system

    Cons:
        - Communication between modules is complex and has many options, all with subtle cost/benefit scenarios.
        - Dependencies discovery and resolution is simple and error-prone.  
    

Asynchronous communications are difficult
