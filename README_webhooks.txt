##############
#### Lista de todos os tipos de Webhooks
##############



  "name" => "BILLING.PLAN.CREATED"
  "description" => "A billing plan is created."
  "status" => "ENABLED"

  "name" => "BILLING.PLAN.UPDATED"
  "description" => "A billing plan is updated."
  "status" => "ENABLED"

  "name" => "BILLING.SUBSCRIPTION.CANCELLED"
  "description" => "A billing agreement is canceled."
  "status" => "ENABLED"

  "name" => "BILLING.SUBSCRIPTION.CREATED"
  "description" => "A billing agreement is created."
  "status" => "ENABLED"

  "name" => "BILLING.SUBSCRIPTION.RE-ACTIVATED"
  "description" => "A billing agreement is re-activated."
  "status" => "ENABLED"

  "name" => "BILLING.SUBSCRIPTION.SUSPENDED"
  "description" => "A billing agreement is suspended."
  "status" => "ENABLED"

  "name" => "BILLING.SUBSCRIPTION.UPDATED"
  "description" => "A billing agreement is updated."
  "status" => "ENABLED"

  "name" => "CHECKOUT.ORDER.COMPLETED"
  "description" => "Webhook event emitted after all the purchase_units have been processed"
  "status" => "ENABLED"

  "name" => "CUSTOMER.DISPUTE.CREATED"
  "description" => "A customer dispute is created."
  "status" => "ENABLED"

  "name" => "CUSTOMER.DISPUTE.RESOLVED"
  "description" => "A customer dispute is resolved."
  "status" => "ENABLED"

  "name" => "CUSTOMER.DISPUTE.UPDATED"
  "description" => "A customer dispute is updated."
  "status" => "ENABLED"

  "name" => "CUSTOMER.MANAGED-ACCOUNT.CREATED"
  "description" => "Webhook event emitted after the non-loginable account have been created."
  "status" => "ENABLED"

  "name" => "CUSTOMER.MANAGED-ACCOUNT.RISK-ASSESSED"
  "description" => "Webhook event emitted after the account has been risk assessed or the risk assessment of the account has been changed."
  "status" => "ENABLED"

  "name" => "CUSTOMER.MANAGED-ACCOUNT.UPDATED"
  "description" => "Webhook event emitted after the non-loginable account have been updated."
  "status" => "ENABLED"

  "name" => "CUSTOMER.PAYOUT.FAILED"
  "description" => "The webhook event payload for the `CUSTOMER.PAYOUTS.FAILED` event."
  "status" => "ENABLED"

  "name" => "IDENTITY.AUTHORIZATION-CONSENT.REVOKED"
  "description" => "A risk dispute is created."
  "status" => "ENABLED"

  "name" => "INVOICING.INVOICE.CANCELLED"
  "description" => "A merchant or customer cancels an invoice."
  "status" => "ENABLED"

  "name" => "INVOICING.INVOICE.CREATED"
  "description" => "An invoice is created."
  "status" => "ENABLED"

  "name" => "INVOICING.INVOICE.PAID"
  "description" => "An invoice is paid, partially paid, or payment is made and is pending."
  "status" => "ENABLED"

  "name" => "INVOICING.INVOICE.REFUNDED"
  "description" => "An invoice is refunded or partially refunded."
  "status" => "ENABLED"

  "name" => "INVOICING.INVOICE.SCHEDULED"
  "description" => "An invoice is scheduled."
  "status" => "ENABLED"

  "name" => "INVOICING.INVOICE.UPDATED"
  "description" => "An invoice is updated."
  "status" => "ENABLED"

  "name" => "MERCHANT.ONBOARDING.COMPLETED"
  "description" => "A merchant completes setup."
  "status" => "ENABLED"

  "name" => "MERCHANT.PARTNER-CONSENT.REVOKED"
  "description" => "The consents for a merchant account setup are revoked or an account is closed."
  "status" => "ENABLED"

  "name" => "PAYMENT-NETWORKS.ALTERNATIVE-PAYMENT.COMPLETED"
  "description" => "Webhook event payload to send for Alternative Payment Completion."
  "status" => "ENABLED"

  "name" => "PAYMENT.AUTHORIZATION.CREATED"
  "description" => "A payment authorization is created, approved, executed, or a future payment authorization is created."
  "status" => "ENABLED"

  "name" => "PAYMENT.AUTHORIZATION.VOIDED"
  "description" => "A payment authorization is voided."
  "status" => "ENABLED"

  "name" => "PAYMENT.CAPTURE.COMPLETED"
  "description" => "A payment capture completes."
  "status" => "ENABLED"

  "name" => "PAYMENT.CAPTURE.DENIED"
  "description" => "A payment capture is denied."
  "status" => "ENABLED"

  "name" => "PAYMENT.CAPTURE.PENDING"
  "description" => "The state of a payment capture changes to pending."
  "status" => "ENABLED"

  "name" => "PAYMENT.CAPTURE.REFUNDED"
  "description" => "A merchant refunds a payment capture."
  "status" => "ENABLED"

  "name" => "PAYMENT.CAPTURE.REVERSED"
  "description" => "PayPal reverses a payment capture."
  "status" => "ENABLED"

  "name" => "PAYMENT.ORDER.CANCELLED"
  "description" => "A payment order is canceled."
  "status" => "ENABLED"

  "name" => "PAYMENT.ORDER.CREATED"
  "description" => "A payment order is created."
  "status" => "ENABLED"

  "name" => "PAYMENT.PAYOUTS-ITEM.BLOCKED"
  "description" => "A payouts item was blocked."
  "status" => "ENABLED"

  "name" => "PAYMENT.PAYOUTS-ITEM.CANCELED"
  "description" => "A payouts item is canceled."
  "status" => "ENABLED"

  "name" => "PAYMENT.PAYOUTS-ITEM.DENIED"
  "description" => "A payouts item is denied."
  "status" => "ENABLED"

  "name" => "PAYMENT.PAYOUTS-ITEM.FAILED"
  "description" => "A payouts item fails."
  "status" => "ENABLED"

  "name" => "PAYMENT.PAYOUTS-ITEM.HELD"
  "description" => "A payouts item is held."
  "status" => "ENABLED"

  "name" => "PAYMENT.PAYOUTS-ITEM.REFUNDED"
  "description" => "A payouts item is refunded."
  "status" => "ENABLED"

  "name" => "PAYMENT.PAYOUTS-ITEM.RETURNED"
  "description" => "A payouts item is returned."
  "status" => "ENABLED"
	
  "name" => "PAYMENT.PAYOUTS-ITEM.SUCCEEDED"
  "description" => "A payouts item succeeds."
  "status" => "ENABLED"

  "name" => "PAYMENT.PAYOUTS-ITEM.UNCLAIMED"
  "description" => "A payouts item is unclaimed."
  "status" => "ENABLED"

  "name" => "PAYMENT.PAYOUTSBATCH.DENIED"
  "description" => "A batch payout payment is denied."
  "status" => "ENABLED"

  "name" => "PAYMENT.PAYOUTSBATCH.PROCESSING"
  "description" => "The state of a batch payout payment changes to processing."
  "status" => "ENABLED"

  "name" => "PAYMENT.PAYOUTSBATCH.SUCCESS"
  "description" => "A batch payout payment completes successfully."
  "status" => "ENABLED"

  "name" => "PAYMENT.SALE.COMPLETED"
  "description" => "A sale completes."
  "status" => "ENABLED"

  "name" => "PAYMENT.SALE.DENIED"
  "description" => "The state of a sale changes from pending to denied."
  "status" => "ENABLED"

  "name" => "PAYMENT.SALE.PENDING"
  "description" => "The state of a sale changes to pending."
  "status" => "ENABLED"

  "name" => "PAYMENT.SALE.REFUNDED"
  "description" => "A merchant refunds a sale."
  "status" => "ENABLED"

  "name" => "PAYMENT.SALE.REVERSED"
  "description" => "PayPal reverses a sale."
  "status" => "ENABLED"

  "name" => "VAULT.CREDIT-CARD.CREATED"
  "description" => "A credit card is created."
  "status" => "ENABLED"

  "name" => "VAULT.CREDIT-CARD.DELETED"
  "description" => "A credit card is deleted."
  "status" => "ENABLED"

  "name" => "VAULT.CREDIT-CARD.UPDATED"
  "description" => "A credit card is updated."
  "status" => "ENABLED"
