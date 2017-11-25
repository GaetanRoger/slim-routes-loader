# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

### [0.2] - 2017-11-25
* ADDED - Loaders can now be called with a `load` method instead of just `__invoke`.
* ADDED - `LoaderInterface` to make sure every loader has a `__invoke` and `load` method.
* ADDED - You can now choose if you want the loaders to throw an exception if a problem
  is encountered (i.e. the provided HTTP method is unknown).

### [0.1] - 2017-11-25
* ADDED - Loader to load from PHP array
* ADDED - JsonLoader to load from Json
* ADDED - YmlLoader to load from Yaml