tests: unit_tests integration_tests

unit_tests:
	./vendor/bin/phpunit --configuration phpunit.xml --testsuite UnitTests

integration_tests:
	./vendor/bin/phpunit --configuration phpunit.xml --testsuite IntegrationTests
