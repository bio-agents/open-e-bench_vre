#!/bin/sh
set -o xtrace   # Write all commands first to stderr
set -o errexit  # Exit the script with error if any of the commands fail


DIR=$(dirname $0)

OS=$(uname -s | tr '[:upper:]' '[:lower:]')
BUILDTOOL=${BUILDTOOL:-autoagents}

case "$OS" in
   cygwin*)
      sh $DIR/compile-windows.sh
   ;;

   *)
      # If compiling using multiple different build agents or variants
      # that require wildly different scripting,
      # this would be a good place to call the different scripts
      case "$BUILDTOOL" in
         cmake)
            sh $DIR/compile-unix-cmake.sh
         ;;
         autoagents)
            sh $DIR/compile-unix.sh
         ;;
      esac
   ;;
esac

