Feature: Validator

  Scenario: Validator should do nothing for method which does not implement right interface
    When I validate method "DemoApp\Method\BasicMethod" with:
    """
    {
      "fieldA": "A",
      "unknownField": "B"
    }
    """
    Then I should have no violation

  Scenario: Validator should return an empty list if there is no violations
    When I validate method "DemoApp\Method\BasicMethodWithRequiredParams" with:
    """
    {
      "fieldA": "plop",
      "fieldB": "plip"
    }
    """
    Then I should have no violation

  Scenario: Validator should return list of violations if there is some
    When I validate method "DemoApp\Method\BasicMethodWithRequiredParams" with:
    """
    {
      "fieldA": null,
      "fieldB": ""
    }
    """
    Then I should have 2 violations
    And I should have the following validation error:
    """
    {
      "path": "[fieldA]",
      "message": "This value should not be null.",
      "code": "ad32d13f-c3d4-423b-909a-857b961eb720"
    }
    """
    And I should have the following validation error:
    """
    {
      "path": "[fieldB]",
      "message": "This value should not be blank.",
      "code":"c1051bb4-d103-4f74-8988-acbcafc7fdc3"
    }
    """
